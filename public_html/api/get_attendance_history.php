<?php
include 'db_connection.php';

header("Content-Type: application/json");

$studentId = isset($_GET['studentId']) ? intval($_GET['studentId']) : 0;
$groupId = isset($_GET['groupId']) ? intval($_GET['groupId']) : 0;

if ($studentId > 0 && $groupId > 0) {
    $stmt = $pdo->prepare("
        SELECT 
            a.date, 
            CASE 
                WHEN a.status = 'on_time' THEN 'Asistencia'
                WHEN a.status = 'absent' THEN 'Ausente'
                WHEN a.status = 'late_a' THEN 'Retardo Tipo A'
                WHEN a.status = 'late_b' THEN 'Retardo Tipo B'
            END AS status
        FROM attendances a
        WHERE a.user_id = :studentId AND a.group_id = :groupId
        ORDER BY a.date DESC
    ");
    $stmt->execute(['studentId' => $studentId, 'groupId' => $groupId]);
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($historial) {
        echo json_encode($historial);
    } else {
        echo json_encode(["error" => "No se encontraron datos de historial para el estudiante y grupo especificados."]);
    }
} else {
    echo json_encode(["error" => "ID de estudiante o grupo no válido."]);
}
?>
