<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'] ?? null;
    $currentPassword = $_POST['current_password'] ?? null;
    $newPassword = $_POST['new_password'] ?? null;

    try {
        // Verificar contraseña actual si se proporciona
        if ($currentPassword) {
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($currentPassword, $user['password'])) {
                echo json_encode(['success' => false, 'message' => 'Contraseña actual incorrecta']);
                exit;
            }
        }

        // Construcción dinámica de la consulta de actualización
        $updates = [];
        if ($name) {
            $updates[] = "name = :name";
        }
        if ($newPassword) {
            $updates[] = "password = :password";
        }

        if ($updates) {
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            if ($name) {
                $stmt->bindValue(':name', $name);
            }
            if ($newPassword) {
                $stmt->bindValue(':password', password_hash($newPassword, PASSWORD_DEFAULT));
            }
            $stmt->bindValue(':id', $id);
            $stmt->execute();
        }

        // Manejo de la foto de perfil
        if (!empty($_FILES['photo']['name'])) {
            // Validar tipo de archivo
            $allowedTypes = ['jpg', 'jpeg', 'png'];
            $fileExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedTypes)) {
                echo json_encode(['success' => false, 'message' => 'El formato de la foto no es válido. Solo se permiten JPG, JPEG y PNG']);
                exit;
            }

            // Validar tamaño del archivo (máximo 10 MB)
            if ($_FILES['photo']['size'] > 10 * 1024 * 1024) { // 10 MB
                echo json_encode(['success' => false, 'message' => 'El tamaño de la foto supera el límite de 10 MB']);
                exit;
            }

            // Guardar la foto en el servidor
            $fileName = '../photos/' . uniqid() . '.' . $fileExtension;
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $fileName)) {
                echo json_encode(['success' => false, 'message' => 'Error al guardar la foto en el servidor']);
                exit;
            }

            // Actualizar la foto en la base de datos
            $pdo->prepare("UPDATE users SET photo = :photo WHERE id = :id")
                ->execute([':photo' => $fileName, ':id' => $id]);
        }

        // Obtener datos actualizados del usuario
        $user = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $user->execute([':id' => $id]);
        $userData = $user->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'user' => $userData]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
