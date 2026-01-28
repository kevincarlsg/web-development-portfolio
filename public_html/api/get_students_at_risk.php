<?php
include 'db_connection.php';

// Verificar si se recibe el parámetro profesor_id
$profesor_id = $_GET['profesor_id'] ?? null;

if (!$profesor_id) {
    echo json_encode(['success' => false, 'message' => 'Falta el parámetro profesor_id']);
    exit;
}

try {
    // Consulta para obtener los alumnos en riesgo que pertenecen únicamente a los grupos del profesor
    $query = $pdo->prepare("
        SELECT u.id, u.name, COUNT(a.id) AS absent_count
        FROM users u
        JOIN attendances a ON u.id = a.user_id
        JOIN groups g ON a.group_id = g.id
        JOIN group_user gu ON gu.user_id = u.id AND gu.group_id = g.id
        WHERE g.profesor_id = :profesor_id AND a.status = 'absent'
        GROUP BY u.id
        HAVING absent_count >= 1
    ");
    $query->execute(['profesor_id' => $profesor_id]);
    $studentsAtRisk = $query->fetchAll(PDO::FETCH_ASSOC);

    if (count($studentsAtRisk) > 0) {
        echo json_encode(['success' => true, 'data' => $studentsAtRisk]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron alumnos en riesgo para este profesor.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $e->getMessage()]);
}
?>
