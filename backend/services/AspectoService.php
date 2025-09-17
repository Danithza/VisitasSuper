<?php
require_once __DIR__ . '/../config/db.php';

class AspectoService {
    private $db;

    public function __construct() {
        global $db; // reutilizamos conexión de db.php
        $this->db = $db;
    }

    // 🔹 Obtener todos los aspectos
    public function getAllAspectos() {
        $sql = "SELECT * FROM aspectos";
        $result = $this->db->query($sql);

        $aspectos = [];
        while ($row = $result->fetch_assoc()) {
            $aspectos[] = $row;
        }

        return $aspectos;
    }

    // 🔹 Obtener un aspecto por ID
    public function getAspectoById($id) {
        $sql = "SELECT * FROM aspectos WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // 🔹 Crear un aspecto
    public function createAspecto($data) {
        $sql = "INSERT INTO aspectos (descripcion) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $data['descripcion']);
        return $stmt->execute();
    }

    // 🔹 Actualizar un aspecto
    public function updateAspecto($id, $data) {
        $sql = "UPDATE aspectos SET descripcion=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $data['descripcion'], $id);
        return $stmt->execute();
    }

    // 🔹 Eliminar un aspecto
    public function deleteAspecto($id) {
        $sql = "DELETE FROM aspectos WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
