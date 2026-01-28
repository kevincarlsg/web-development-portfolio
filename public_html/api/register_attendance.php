<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? null;
    $groupId = $_POST['group_id'] ?? null;
    $status = $_POST['status'] ?? 'on_time'; // Puede ser: 'on_time', 'late_a', 'late_b', 'absent'
    $date = date('Y-m-d'); // Fecha actual

    if (!$userId || !$groupId) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    try {
        // Verificar si el usuario es un alumno
        $roleCheckQuery = "SELECT role FROM users WHERE id = :user_id";
        $roleCheckStmt = $pdo->prepare($roleCheckQuery);
        $roleCheckStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $roleCheckStmt->execute();
        $user = $roleCheckStmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['role'] !== 'alumno') {
            echo json_encode(['success' => false, 'message' => 'Solo los alumnos pueden registrar asistencia.']);
            exit;
        }

        // Comprobar si ya existe un registro de asistencia para este usuario, grupo y fecha
        $checkQuery = "SELECT id FROM attendances WHERE user_id = :user_id AND group_id = :group_id AND date = :date";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $checkStmt->bindParam(':group_id', $groupId, PDO::PARAM_INT);
        $checkStmt->bindParam(':date', $date, PDO::PARAM_STR);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            // Si ya existe, actualizamos el estado y la fecha de actualización
            $updateQuery = "UPDATE attendances SET status = :status, updated_at = NOW() 
                            WHERE user_id = :user_id AND group_id = :group_id AND date = :date";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
            $updateStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $updateStmt->bindParam(':group_id', $groupId, PDO::PARAM_INT);
            $updateStmt->bindParam(':date', $date, PDO::PARAM_STR);

            if ($updateStmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Asistencia actualizada correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la asistencia.']);
            }
        } else {
            // Si no existe, insertamos un nuevo registro
            $insertQuery = "INSERT INTO attendances (user_id, group_id, date, status, created_at, updated_at) 
                            VALUES (:user_id, :group_id, :date, :status, NOW(), NOW())";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $insertStmt->bindParam(':group_id', $groupId, PDO::PARAM_INT);
            $insertStmt->bindParam(':date', $date, PDO::PARAM_STR);
            $insertStmt->bindParam(':status', $status, PDO::PARAM_STR);

            if ($insertStmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Asistencia registrada correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al registrar la asistencia.']);
            }
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>