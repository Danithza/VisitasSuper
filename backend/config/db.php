<?php
$servername = "127.0.0.1";  
$username   = "root";
$password   = "";
$dbname     = "visitassuper"; // ⚠️ ojo al nombre, en tu screenshot se ve "visitassuper"
$port       = 3307;

$db = new mysqli($servername, $username, $password, $dbname, $port);

if ($db->connect_error) {
    die("❌ Conexión fallida: " . $db->connect_error);
}
