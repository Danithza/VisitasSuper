<?php 
// backend/controllers/visitasController.php
require_once __DIR__ . '/../config/db.php';

/* --- Helpers --- */
function respond($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

/* --- VISITAS --- */
function handleVisitas() {
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            isset($_GET['id']) ? getVisita($_GET['id']) : getAllVisitas();
            break;
        case 'POST':
            createVisita();
            break;
        case 'PUT':
            updateVisita();
            break;
        case 'DELETE':
            deleteVisita();
            break;
        default:
            respond(['error' => 'Método no permitido'], 405);
    }
}

function getAllVisitas() {
    global $conexion;
    $sql = "SELECT v.*, a.descripcion AS aspecto, r.nombre AS responsable, r.correo
            FROM visitas v
            LEFT JOIN aspectos a ON v.aspecto_id = a.id
            LEFT JOIN responsables r ON v.responsable_id = r.id
            ORDER BY v.fecha_inicio DESC";
    $res = $conexion->query($sql);
    $rows = $res->fetch_all(MYSQLI_ASSOC);
    respond($rows);
}

function getVisita($id) {
    global $conexion;
    $stmt = $conexion->prepare(
        "SELECT v.*, a.descripcion AS aspecto, r.nombre AS responsable, r.correo
         FROM visitas v
         LEFT JOIN aspectos a ON v.aspecto_id = a.id
         LEFT JOIN responsables r ON v.responsable_id = r.id
         WHERE v.id = ?"
    );
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $row ? respond($row) : respond(['error' => 'No encontrado'], 404);
}

function createVisita() {
    global $conexion;

    // campos enviados
    $nombre_visita   = $_POST['nombre_visita'] ?? '';
    $fecha_inicio    = $_POST['fecha_inicio'] ?? null;
    $fecha_fin       = $_POST['fecha_fin'] ?? null;
    $aspecto_id      = $_POST['aspecto_id'] ?? null;
    $observacion     = $_POST['observacion'] ?? null;
    $recurrente      = $_POST['recurrente'] ?? null;
    $plazo_fecha     = $_POST['plazo_fecha'] ?? null;
    $actividad       = $_POST['actividad'] ?? null;
    $responsable_id  = $_POST['responsable_id'] ?? null;
    $estado          = $_POST['estado'] ?? null;

    // --- Manejo de la evidencia (archivo) ---
    $evidenciaPath = null;
    if (!empty($_FILES['evidencia']) && $_FILES['evidencia']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES['evidencia']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['evidencia']['tmp_name'], $targetFile)) {
            $evidenciaPath = $fileName;
        }
    }

    $stmt = $conexion->prepare(
        "INSERT INTO visitas
        (fecha_inicio, fecha_fin, nombre_visita, aspecto_id, observacion, recurrente, plazo_fecha, actividad, responsable_id, estado, evidencia)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        'sssissssiss',
        $fecha_inicio,
        $fecha_fin,
        $nombre_visita,
        $aspecto_id,
        $observacion,
        $recurrente,
        $plazo_fecha,
        $actividad,
        $responsable_id,
        $estado,
        $evidenciaPath
    );

    if ($stmt->execute()) {
        respond(['message' => 'Visita creada', 'id' => $stmt->insert_id], 201);
    } else {
        respond(['error' => $stmt->error], 500);
    }
}

function updateVisita() {
    global $conexion;
    if (!isset($_GET['id'])) respond(['error' => 'Falta id en la URL (?id=)'], 400);
    $id = intval($_GET['id']);
    parse_str(file_get_contents("php://input"), $d);

    $cols = ['fecha_inicio','fecha_fin','nombre_visita','aspecto_id','observacion','recurrente','plazo_fecha','actividad','responsable_id','estado','evidencia'];
    $fields = [];
    $params = [];

    foreach ($cols as $c) {
        if (isset($d[$c])) {
            $fields[] = "$c = ?";
            $params[] = $d[$c];
        }
    }

    if (empty($fields)) respond(['error' => 'Nada para actualizar'], 400);

    $sql = "UPDATE visitas SET " . implode(', ', $fields) . " WHERE id = ?";
    $params[] = $id;

    $types = str_repeat('s', count($params));
    $stmt = $conexion->prepare($sql);

    $bind = [];
    $bind[] = &$types;
    foreach ($params as $k => $v) $bind[] = &$params[$k];

    call_user_func_array([$stmt, 'bind_param'], $bind);

    $stmt->execute() ? respond(['message' => 'Actualizado']) : respond(['error' => $stmt->error], 500);
}

function deleteVisita() {
    global $conexion;
    if (!isset($_GET['id'])) respond(['error' => 'Falta id en la URL (?id=)'], 400);
    $id = intval($_GET['id']);
    $stmt = $conexion->prepare("DELETE FROM visitas WHERE id = ?");
    $stmt->bind_param('i',$id);
    $stmt->execute() ? respond(['message' => 'Eliminado']) : respond(['error' => $stmt->error], 500);
}

/* --- ASPECTOS --- */
function handleAspectos() {
    global $conexion;
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $res = $conexion->query("SELECT * FROM aspectos ORDER BY id ASC");
        respond($res->fetch_all(MYSQLI_ASSOC));
    } elseif ($method === 'POST') {
        $d = json_decode(file_get_contents("php://input"), true);
        if (empty($d['descripcion'])) respond(['error' => 'descripcion vacía'],400);

        $stmt = $conexion->prepare("INSERT INTO aspectos (descripcion) VALUES (?)");
        $stmt->bind_param('s', $d['descripcion']);
        $stmt->execute()
            ? respond(['message'=>'Aspecto creado','id'=>$stmt->insert_id],201)
            : respond(['error'=>$stmt->error],500);
    } else {
        respond(['error' => 'Método no permitido'],405);
    }
}

/* --- RESPONSABLES --- */
function handleResponsables() {
    global $conexion;
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $res = $conexion->query("SELECT * FROM responsables ORDER BY nombre ASC");
        respond($res->fetch_all(MYSQLI_ASSOC));
    } elseif ($method === 'POST') {
        $d = json_decode(file_get_contents("php://input"), true);
        if (empty($d['nombre']) || empty($d['correo'])) respond(['error' => 'nombre y correo obligatorios'],400);

        $stmt = $conexion->prepare("INSERT INTO responsables (nombre, correo) VALUES (?, ?)");
        $stmt->bind_param('ss', $d['nombre'], $d['correo']);
        $stmt->execute()
            ? respond(['message'=>'Responsable creado','id'=>$stmt->insert_id],201)
            : respond(['error'=>$stmt->error],500);
    } else {
        respond(['error' => 'Método no permitido'],405);
    }
}
