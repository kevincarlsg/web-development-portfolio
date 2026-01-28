<?php
// db_connection.php
$host = '127.0.0.1'; // Cambia esto si tu servidor es diferente
$db = 'u130047665_asistencia';  // Nombre de tu base de datos
$user = 'u130047665_classscan';      // Usuario de la base de datos
$pass = 'Class_Scan_Hash69';          // Contraseña de la base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
