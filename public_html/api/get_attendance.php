<?php
include 'db_connection.php';

header("Content-Type: application/json");

// Obtener y validar los parámetros de entrada
$studentId = isset($_GET['studentId']) ? intval($_GET['studentId']) : 0;
$groupId = isset($_GET['groupId']) ? intval($_GET['groupId']) : 0;

if ($studentId > 0 && $groupId > 0) {
    // Consulta para obtener los registros de asistencia, hacer los conteos y obtener la tolerancia del grupo
    $stmt = $pdo->prepare("
        SELECT 
            u.name AS nombre, 
            u.email,
            g.name AS grupo,
            g.tolerance,
            u.role AS rol,
            SUM(CASE WHEN a.status = 'on_time' THEN 1 ELSE 0 END) AS asistencias,
            SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) AS faltas,
            SUM(CASE WHEN a.status = 'late_a' THEN 1 ELSE 0 END) AS retardosTipoA,
            SUM(CASE WHEN a.status = 'late_b' THEN 1 ELSE 0 END) AS retardosTipoB
        FROM attendances a
        JOIN users u ON a.user_id = u.id
        JOIN groups g ON a.group_id = g.id
        WHERE a.user_id = :studentId AND a.group_id = :groupId
        GROUP BY u.id, g.id
    ");
    
    // Ejecutar la consulta con los parámetros
    $stmt->execute(['studentId' => $studentId, 'groupId' => $groupId]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        echo json_encode([
            "nombre" => $data['nombre'],
            "email" => $data['email'],
            "grupo" => $data['grupo'],
            "rol" => $data['rol'],
            "tolerance" => $data['tolerance'], // Agrega tolerancia al resultado
            "asistencias" => $data['asistencias'],
            "faltas" => $data['faltas'],
            "retardosTipoA" => $data['retardosTipoA'],
            "retardosTipoB" => $data['retardosTipoB']
        ]);
    } else {
        echo json_encode(["error" => "No se encontraron datos para el estudiante y grupo especificados."]);
    }
} else {
    echo json_encode(["error" => "ID de estudiante o grupo no válido."]);
}
?>
