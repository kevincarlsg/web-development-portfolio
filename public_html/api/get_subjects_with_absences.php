<?php
include 'db_connection.php';

$user_id = $_GET['user_id']; // Recibir el id del usuario desde Flutter

$query = $pdo->prepare("
  SELECT s.name, COUNT(a.id) as absences
  FROM subjects s
  JOIN groups g ON s.id = g.subject_id
  JOIN attendances a ON g.id = a.group_id
  WHERE a.user_id = :user_id AND a.status = 'absent'
  GROUP BY s.id
");
$query->execute(['user_id' => $user_id]);
$subjectsWithAbsences = $query->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($subjectsWithAbsences);
?>
