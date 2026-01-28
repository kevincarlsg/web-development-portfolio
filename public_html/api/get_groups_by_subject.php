<?php
// Incluye el archivo de conexión con PDO (db_connection.php)
include 'db_connection.php';

// Obtiene los parámetros de la URL: ID de la materia y ID del profesor
$subjectId = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : 0;
$profesorId = isset($_GET['profesor_id']) ? intval($_GET['profesor_id']) : 0;

// Verifica que los parámetros sean válidos
if ($subjectId > 0 && $profesorId > 0) {
    try {
        // Prepara la consulta para obtener los grupos asociados a la materia y al profesor
        $query = $pdo->prepare("
            SELECT id, name, class_schedule 
            FROM groups 
            WHERE subject_id = :subject_id AND profesor_id = :profesor_id
        ");
        // Ejecuta la consulta con los parámetros adecuados
        $query->execute([
            'subject_id' => $subjectId,
            'profesor_id' => $profesorId
        ]);

        // Obtiene los resultados como un array asociativo
        $groups = $query->fetchAll(PDO::FETCH_ASSOC);

        // Establece el encabezado de respuesta a JSON y envía los datos
        header('Content-Type: application/json');
        echo json_encode($groups);
    } catch (Exception $e) {
        // Maneja cualquier error que ocurra durante la ejecución de la consulta
        header('Content-Type: application/json');
        echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
    }
} else {
    // Devuelve un error si los parámetros no son válidos
    header('Content-Type: application/json');
    echo json_encode(["error" => "ID de materia o profesor inválido"]);
}
?>
