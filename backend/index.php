<?php
// backend/index.php

header('Content-Type: application/json; charset=utf-8');

// --- Router dinámico ---
// 1. Tomamos el nombre del controlador y acción desde la URL
$controllerName = $_GET['controller'] ?? 'Visitas';
$action = $_GET['action'] ?? 'index';

// 2. Armamos el nombre del archivo y de la clase
$controllerFile = __DIR__ . "/controllers/{$controllerName}Controller.php";
$controllerClass = $controllerName . "Controller";

try {
    // 3. Validamos si existe el archivo del controlador
    if (!file_exists($controllerFile)) {
        http_response_code(404);
        echo json_encode(['error' => "Controlador {$controllerName} no encontrado"]);
        exit;
    }

    // 4. Cargamos el controlador
    require_once $controllerFile;

    if (!class_exists($controllerClass)) {
        http_response_code(500);
        echo json_encode(['error' => "Clase {$controllerClass} no definida"]);
        exit;
    }

    $controller = new $controllerClass();

    // 5. Validamos si la acción existe en el controlador
    if (!method_exists($controller, $action)) {
        http_response_code(404);
        echo json_encode(['error' => "Acción {$action} no encontrada en {$controllerClass}"]);
        exit;
    }

    // 6. Pasamos parámetros si los hay (ej: id)
    $params = $_GET;
    unset($params['controller'], $params['action']);

    // Llamamos dinámicamente el método con parámetros
    $result = call_user_func_array([$controller, $action], $params);

    echo json_encode($result);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
