<?php
include 'db_connection.php';

$profesor_id = $_GET['profesor_id']; // Recibir el id del profesor desde Flutter

$query = $pdo->prepare("SELECT * FROM groups WHERE profesor_id = :profesor_id");
$query->execute(['profesor_id' => $profesor_id]);
$groups = $query->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($groups);
?>
