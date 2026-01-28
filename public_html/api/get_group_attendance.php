<?php
include 'db_connection.php';

$group_id = $_GET['group_id']; // Recibir el id del grupo desde Flutter

// Obtener el historial de asistencias
$query = $pdo->prepare("
  SELECT date, status
  FROM attendances
  WHERE group_id = :group_id
  ORDER BY date DESC
");
$query->execute(['group_id' => $group_id]);
$history = $query->fetchAll(PDO::FETCH_ASSOC);

// Obtener las estadísticas de asistencias
$statsQuery = $pdo->prepare("
  SELECT 
    SUM(status = 'on_time') as on_time,
    SUM(status = 'late_a') as late_a,
    SUM(status = 'late_b') as late_b,
    SUM(status = 'absent') as absent
  FROM attendances
  WHERE group_id = :group_id
");
$statsQuery->execute(['group_id' => $group_id]);
$stats = $statsQuery->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode(['history' => $history, 'stats' => $stats]);
?>
