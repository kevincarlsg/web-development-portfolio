<?php
include 'db_connection.php'; // Incluye tu archivo de conexión a la base de datos

// Configura la zona horaria
date_default_timezone_set('America/Mexico_City'); // Cambia según tu zona horaria

// Verifica que el método de la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Verifica los parámetros necesarios
if (!isset($_POST['group_id'])) {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(['success' => false, 'message' => 'Parámetro group_id faltante']);
    exit;
}

$groupId = $_POST['group_id'];

try {
    // Verifica la conexión a la base de datos
    if (!$pdo) {
        throw new Exception('Conexión a la base de datos fallida');
    }

    // Consulta para obtener el intervalo QR del grupo
    $stmt = $pdo->prepare("SELECT qr_interval FROM groups WHERE id = :group_id");
    $stmt->execute([':group_id' => $groupId]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$group) {
        http_response_code(404); // No encontrado
        echo json_encode(['success' => false, 'message' => 'Grupo no encontrado']);
        exit;
    }

    $qrInterval = (int)$group['qr_interval']; // Intervalo del QR en minutos

    // Calcula la fecha y hora actuales
    $currentTime = new DateTime(); // Hora actual
    $qrGeneratedAt = clone $currentTime; // Clona para calcular
    $qrGeneratedAt->modify("-{$qrInterval} minutes"); // Hora de generación del QR
    $qrExpirationTime = clone $qrGeneratedAt; // Clona para calcular
    $qrExpirationTime->modify("+{$qrInterval} minutes"); // Hora de expiración del QR

    // Verifica si el QR está activo
    $isActive = $currentTime >= $qrGeneratedAt && $currentTime <= $qrExpirationTime;

    // Respuesta en JSON
    echo json_encode([
        'success' => true,
        'is_active' => $isActive,
        'qr_generated_at' => $qrGeneratedAt->format('Y-m-d H:i:s'),
        'qr_expires_at' => $qrExpirationTime->format('Y-m-d H:i:s'),
        'message' => $isActive ? 'El QR está activo' : 'El QR ha expirado',
    ]);
} catch (Exception $e) {
    // Manejo de excepciones y errores del servidor
    http_response_code(500); // Error interno del servidor
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
