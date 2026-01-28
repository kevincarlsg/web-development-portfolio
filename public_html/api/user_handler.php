<?php
include 'db_connection.php';

header("Content-Type: application/json");

// Obtener y validar el parámetro de entrada `studentId`
$studentId = isset($_GET['studentId']) ? intval($_GET['studentId']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validación para el método GET
    if ($studentId > 0) {
        // Consulta para obtener los datos del estudiante
        $stmt = $pdo->prepare("
            SELECT 
                u.id AS studentId,
                u.name AS nombre, 
                u.email,
                u.role AS rol
            FROM users u
            WHERE u.id = :studentId
        ");
        
        $stmt->execute(['studentId' => $studentId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Enviar respuesta en JSON
        if ($data) {
            echo json_encode($data);
        } else {
            echo json_encode(["error" => "Estudiante no encontrado"]);
        }
    } else {
        echo json_encode(["error" => "ID de estudiante no válido"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Manejo del método POST para actualizar el rol del usuario
    $input = json_decode(file_get_contents("php://input"), true);
    $studentId = isset($input['studentId']) ? intval($input['studentId']) : 0;
    $newRole = isset($input['role']) ? $input['role'] : null;

    if ($studentId > 0 && in_array($newRole, ['alumno', 'profesor'])) {
        $stmt = $pdo->prepare("UPDATE users SET role = :role WHERE id = :studentId");
        $stmt->execute(['role' => $newRole, 'studentId' => $studentId]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "message" => "Rol actualizado exitosamente"]);
        } else {
            echo json_encode(["error" => "No se pudo actualizar el rol del estudiante"]);
        }
    } else {
        echo json_encode(["error" => "Datos inválidos para actualizar el rol"]);
    }
} else {
    echo json_encode(["error" => "Método no soportado"]);
}
?>
