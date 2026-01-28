<?php
header('Content-Type: application/json');
require_once 'db_connection.php'; // Incluye el archivo de conexión a la base de datos

if (isset($_GET['group_id'])) {
    $groupId = intval($_GET['group_id']);

    try {
        $query = $pdo->prepare("
            SELECT u.id, u.name, u.email
            FROM group_user gu
            JOIN users u ON gu.user_id = u.id
            WHERE gu.group_id = :group_id
        ");
        $query->bindParam(':group_id', $groupId, PDO::PARAM_INT);
        $query->execute();

        $students = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($students) {
            echo json_encode([
                'success' => true,
                'students' => $students
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'students' => [],
                'message' => 'No hay estudiantes asignados a este grupo.'
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error al procesar los datos del servidor.',
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
