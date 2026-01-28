<?php
include 'db_connection.php';

$profesor_id = $_GET['profesor_id'] ?? null;

if (!$profesor_id) {
    echo json_encode(['success' => false, 'message' => 'ID de profesor no proporcionado']);
    exit;
}

try {
    $query = $pdo->prepare("
        SELECT j.*, a.user_id AS alumno_id, g.name AS grupo_name
        FROM justificantes j
        JOIN attendances a ON j.attendance_id = a.id
        JOIN groups g ON a.group_id = g.id
        WHERE g.profesor_id = :profesor_id AND j.estado = 'pendiente'
    ");
    $query->execute(['profesor_id' => $profesor_id]);
    $justificantes = $query->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($justificantes);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
