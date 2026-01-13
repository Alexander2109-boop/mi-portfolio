<?php
require_once __DIR__ . '/../../CapaNegocio/UsuarioNegocio.php'; // Importa la clase de negocio encargada de registrar usuarios

header('Content-Type: application/json'); // Indica que todas las respuestas serán enviadas en formato JSON

// Verifica si la petición recibida es POST (registro de usuario)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtiene el cuerpo de la solicitud (JSON) y lo convierte a un array asociativo
        $data = json_decode(file_get_contents('php://input'), true);

        // Extrae los valores recibidos o asigna cadenas vacías en caso de no existir
        $nombre = $data['nombre'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        // Instancia la capa de negocio para manejar usuarios
        $usuarioNegocio = new UsuarioNegocio();

        // Ejecuta el método para registrar un nuevo usuario
        $resultado = $usuarioNegocio->registrar($nombre, $email, $password);

        // Si el resultado es true, indica que el registro fue exitoso
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente']);
        } else {
            // Si la capa de negocio devuelve false, lanza una excepción manualmente
            throw new Exception('Error al registrar usuario');
        }
    } catch (Exception $e) {
        // Manejo de errores: establece el código HTTP y envía JSON con el mensaje de error
        http_response_code(400); // Error en la solicitud
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
