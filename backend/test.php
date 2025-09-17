<?php
require_once __DIR__ . '/config/db.php';

$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "✅ Conexión exitosa a la BD";
} else {
    echo "❌ No se pudo conectar";
}
