<?php
include 'db_connection.php';

$user_id = $_GET['user_id']; // Recibir el id del usuario desde Flutter

$query = $pdo->prepare("
  SELECT g.* 
  FROM groups g
  JOIN group_user gu ON g.id = gu.group_id
  WHERE gu.user_id = :user_id
");
$query->execute(['user_id' => $user_id]);
$groups = $query->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($groups);
?>
