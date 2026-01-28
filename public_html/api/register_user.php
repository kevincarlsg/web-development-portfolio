<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(['error' => 'Todos los campos son obligatorios']);
        exit;
    }

    try {
        // Verificar si el correo ya existe en la base de datos
        $checkEmailStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $checkEmailStmt->execute([$email]);
        $emailExists = $checkEmailStmt->fetchColumn() > 0;

        if ($emailExists) {
            echo json_encode(['error' => 'El correo ya está registrado']);
            exit;
        }

        // Encriptar la contraseña
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insertar el usuario en la base de datos
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $hashedPassword])) {
            echo json_encode(['success' => true, 'message' => 'Usuario registrado con éxito']);
        } else {
            echo json_encode(['error' => 'Error al registrar el usuario']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
?>
