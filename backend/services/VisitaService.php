<?php
require_once __DIR__ . '/../config/db.php';

class VisitaService {
    private $db;

    public function __construct() {
        global $db;      // reutilizamos conexión de db.php
        $this->db = $db;
    }

    public function getAllVisitas() {
        $sql = "SELECT * FROM visitas";
        $result = $this->db->query($sql);

        $visitas = [];
        while ($row = $result->fetch_assoc()) {
            $visitas[] = $row;
        }

        return $visitas;
    }

    public function getVisitaById($id) {
        $sql = "SELECT * FROM visitas WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createVisita($data) {
        $sql = "INSERT INTO visitas (fecha_inicio, fecha_fin, nombre_visita, aspecto_id, observacion, actividad, responsable_id, evidencia) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "sssissis",
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['nombre_visita'],
            $data['aspecto_id'],
            $data['observacion'],
            $data['actividad'],
            $data['responsable_id'],
            $data['evidencia']
        );
        return $stmt->execute();
    }

    public function updateVisita($id, $data) {
        $sql = "UPDATE visitas 
                   SET fecha_inicio=?, fecha_fin=?, nombre_visita=?, aspecto_id=?, observacion=?, actividad=?, responsable_id=?, evidencia=? 
                 WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "sssissisi",
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['nombre_visita'],
            $data['aspecto_id'],
            $data['observacion'],
            $data['actividad'],
            $data['responsable_id'],
            $data['evidencia'],
            $id
        );
        return $stmt->execute();
    }

    public function deleteVisita($id) {
        $sql = "DELETE FROM visitas WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
