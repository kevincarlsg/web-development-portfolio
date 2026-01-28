<?php
header('Content-Type: application/json');
require_once 'db_connection.php'; // Incluye el archivo de conexión a la base de datos

if (isset($_GET['group_id'])) {
    $groupId = intval($_GET['group_id']);

    try {
        $query = $pdo->prepare("
            SELECT g.name, s.name AS subject, sc.name AS school, u.name AS professor,
                   g.class_days, g.class_schedule, g.school_period, g.tolerance
            FROM groups g
            JOIN subjects s ON g.subject_id = s.id
            JOIN schools sc ON g.school_id = sc.id
            JOIN users u ON g.profesor_id = u.id
            WHERE g.id = :group_id
        ");
        $query->bindParam(':group_id', $groupId, PDO::PARAM_INT);
        $query->execute();

        $groupDetails = $query->fetch(PDO::FETCH_ASSOC);

        if ($groupDetails) {
            echo json_encode([
                'success' => true,
                'groupDetails' => $groupDetails
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Grupo no encontrado.'
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error al procesar los datos del servidor',
            'details' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Parámetro group_id es requerido.'
    ]);
}
?>
