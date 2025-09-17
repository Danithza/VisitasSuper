<?php
require_once __DIR__ . '/../config/db.php';

class ResponsableService {
    private $db;

    public function __construct() {
        global $db; // reutilizamos conexión de db.php
        $this->db = $db;
    }

    public function getAllResponsables() {
        $sql = "SELECT * FROM responsables";
        $result = $this->db->query($sql);

        $responsables = [];
        while ($row = $result->fetch_assoc()) {
            $responsables[] = $row;
        }

        return $responsables;
    }

    public function getResponsableById($id) {
        $sql = "SELECT * FROM responsables WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createResponsable($data) {
        $sql = "INSERT INTO responsables (nombre, correo, telefono) 
                VALUES (?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "sss",
            $data['nombre'],
            $data['correo'],
            $data['telefono']
        );

        return $stmt->execute();
    }

    public function updateResponsable($id, $data) {
        $sql = "UPDATE responsables 
                   SET nombre=?, correo=?, telefono=? 
                 WHERE id=?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "sssi",
            $data['nombre'],
            $data['correo'],
            $data['telefono'],
            $id
        );

        return $stmt->execute();
    }

    public function deleteResponsable($id) {
        $sql = "DELETE FROM responsables WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
