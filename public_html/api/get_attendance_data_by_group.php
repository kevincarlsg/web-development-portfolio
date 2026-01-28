<?php
header('Content-Type: application/json');
require_once 'db_connection.php'; // Asegúrate de tener tu archivo de conexión a la base de datos

if (isset($_GET['user_id']) && isset($_GET['group_id'])) {
    $userId = intval($_GET['user_id']);
    $groupId = intval($_GET['group_id']);

    try {
        // Consulta para obtener los datos de asistencia que coincidan con user_id y group_id
        $query = $pdo->prepare("
            SELECT date, status, retardos_tipo_a, retardos_tipo_b
            FROM attendances 
            WHERE user_id = :user_id AND group_id = :group_id
            ORDER BY date DESC
        ");
        $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $query->bindParam(':group_id', $groupId, PDO::PARAM_INT);
        $query->execute();

        $attendances = $query->fetchAll(PDO::FETCH_ASSOC);

        // Devuelve los datos como un array JSON
        echo json_encode($attendances);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Error al procesar los datos del servidor',
            'details' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        'error' => 'Faltan parámetros: user_id y group_id son obligatorios'
    ]);
}
