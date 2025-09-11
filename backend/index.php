<?php
// backend/index.php (router simple para el frontend)
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$resource = $_GET['resource'] ?? '';

require __DIR__ . '/controllers/visitasController.php';

switch ($resource) {
    case 'visitas':
        handleVisitas();
        break;
    case 'aspectos':
        handleAspectos();
        break;
    case 'responsables':
        handleResponsables();
        break;
    default:
        http_response_code(400);
        echo json_encode([
            'error' => 'Recurso no especificado o desconocido. Usa ?resource=visitas|aspectos|responsables'
        ]);
        break;
}
