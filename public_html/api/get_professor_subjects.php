<?php
include 'db_connection.php';

$profesor_id = $_GET['profesor_id']; // Recibir el id del profesor desde Flutter

$query = $pdo->prepare("
  SELECT DISTINCT s.* 
  FROM subjects s
  JOIN groups g ON s.id = g.subject_id
  WHERE g.profesor_id = :profesor_id
");
$query->execute(['profesor_id' => $profesor_id]);
$subjects = $query->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($subjects);
?>
