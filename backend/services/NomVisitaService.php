<?php
require_once __DIR__ . '/../config/db.php';

class NomVisitaService {
    private $db;

    public function __construct() {
        global $db; // reutilizamos la conexión creada en db.php
        $this->db = $db;
    }

    public function getAllNomVisita() {
        $sql = "SELECT * FROM nomvisitas";
        $result = $this->db->query($sql);

        $nomVisitas = [];
        while ($row = $result->fetch_assoc()) {
            $nomVisitas[] = $row;
        }

        return $nomVisitas;
    }

    public function getNomVisitaById($id) {
        $sql = "SELECT * FROM nomvisitas WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createNomVisita($data) {
        $sql = "INSERT INTO nomvisitas (NomVisita) VALUES (?)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "s",
            $data['NomVisita']
        );

        return $stmt->execute();
    }

    public function updateNomVisita($id, $data) {
        $sql = "UPDATE nomvisitas 
                   SET NomVisita=? 
                 WHERE id=?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "si",
            $data['NomVisita'],
            $id
        );

        return $stmt->execute();
    }

    public function deleteNomVisita($id) {
        $sql = "DELETE FROM nomvisitas WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
