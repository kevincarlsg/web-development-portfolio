<?php
// Incluye el archivo de conexión con PDO (db_connection.php)
include 'db_connection.php';

// Obtén el ID del usuario desde los parámetros de la URL
$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($userId > 0) {
    try {
        // Consulta para obtener los detalles del usuario, sin el campo total_absences
        $query = $pdo->prepare("SELECT name, email, photo, role FROM users WHERE id = :user_id");
        $query->execute(['user_id' => $userId]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Si el usuario tiene una foto, devolverla; de lo contrario, usar una predeterminada
            if (empty($user['photo'])) {
                $user['photo'] = 'path/to/default_avatar.png'; // Ruta a la imagen predeterminada
            }

            header('Content-Type: application/json');
            echo json_encode($user);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Usuario no encontrado']);
        }
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'ID de usuario inválido']);
}
?>
