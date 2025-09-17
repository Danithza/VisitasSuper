<?php
require_once __DIR__ . '/../config/db.php';

class DashboardService {
    private $db;

    public function __construct() {
        global $db; // conexión de db.php
        $this->db = $db;
    }

    public function getResumen() {
        $resumen = [];

        // Total de visitas
        $sql = "SELECT COUNT(*) as total_visitas FROM visitas";
        $resumen['total_visitas'] = $this->db->query($sql)->fetch_assoc()['total_visitas'];

        // Total de aspectos
        $sql = "SELECT COUNT(*) as total_aspectos FROM aspectos";
        $resumen['total_aspectos'] = $this->db->query($sql)->fetch_assoc()['total_aspectos'];

        // Total de responsables
        $sql = "SELECT COUNT(*) as total_responsables FROM responsables";
        $resumen['total_responsables'] = $this->db->query($sql)->fetch_assoc()['total_responsables'];

        return $resumen;
    }

    public function visitasPorMes() {
        $sql = "SELECT MONTH(fecha_inicio) as mes, COUNT(*) as total 
                  FROM visitas 
              GROUP BY MONTH(fecha_inicio)";
        $result = $this->db->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }
}
