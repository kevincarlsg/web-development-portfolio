<?php
include 'db_connection.php';

$user_id = $_GET['user_id']; // Recibir el id del usuario desde Flutter

$query = $pdo->prepare("
  SELECT s.* 
  FROM schools s
  JOIN groups g ON s.id = g.school_id
  JOIN group_user gu ON g.id = gu.group_id
  WHERE gu.user_id = :user_id
  GROUP BY s.id
");
$query->execute(['user_id' => $user_id]);
$schools = $query->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($schools);
?>