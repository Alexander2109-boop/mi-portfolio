<?php
require_once __DIR__ . '/../../CapaNegocio/UsuarioNegocio.php'; // Importa la capa de negocio encargada de manejar usuarios
session_start(); // Inicia la sesión para manejar autenticación y variables de usuario

header('Content-Type: application/json'); // Define que todas las respuestas serán en formato JSON

// Verifica si la petición es de tipo POST (para autenticación/login)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtiene los datos enviados en el cuerpo de la petición (JSON)
        $data = json_decode(file_get_contents('php://input'), true);

        // Extrae los valores email y password, o asigna cadena vacía si no existen
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        // Instancia la lógica de negocio del usuario
        $usuarioNegocio = new UsuarioNegocio();

        // Llama al método para autenticar al usuario
        $usuario = $usuarioNegocio->autenticar($email, $password);

        // Si la autenticación es correcta, establece las variables de sesión del usuario
        $_SESSION['usuario_id'] = $usuario->getId();
        $_SESSION['usuario_nombre'] = $usuario->getNombre();
        $_SESSION['usuario_email'] = $usuario->getEmail();
        $_SESSION['usuario_rol'] = $usuario->getRolId();

        // Respuesta JSON indicando éxito e incluyendo el nombre del usuario
        echo json_encode(['success' => true, 'nombre' => $usuario->getNombre()]);
    } catch (Exception $e) {
        // Si ocurre un error (credenciales incorrectas, usuario no existe, etc.)
        http_response_code(401); // Código HTTP 401 (No autorizado)
        echo json_encode(['success' => false, 'error' => $e->getMessage()]); // Envía el error en JSON
    }
}
