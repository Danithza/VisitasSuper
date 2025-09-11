<?php
$servername = "127.0.0.1";  
$username   = "root";
$password   = "";
$dbname     = "visitassuper";
$port       = 3307;

$conexion = new mysqli($servername, $username, $password, $dbname, $port);

if ($conexion->connect_error) {
    die("❌ Conexión fallida: " . $conexion->connect_error);
}
?>
