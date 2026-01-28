<?php
include 'db_connection.php';

// Verificar si los datos se han enviado correctamente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta para obtener el usuario por email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el correo electrónico existe
    if (!$user) {
        echo json_encode([
            "success" => false,
            "field" => "email", // Indica que el error está en el correo electrónico
            "message" => "El correo electrónico no está registrado"
        ]);
        exit;
    }

    // Verificar si la contraseña es correcta
    if (!password_verify($password, $user['password'])) {
        echo json_encode([
            "success" => false,
            "field" => "password", // Indica que el error está en la contraseña
            "message" => "La contraseña es incorrecta"
        ]);
        exit;
    }

    // El usuario se ha autenticado correctamente
    echo json_encode([
        "success" => true,
        "user" => [
            "id" => $user['id'],
            "name" => $user['name'],
            "email" => $user['email'],
            "role" => $user['role']
        ]
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
}
?>
