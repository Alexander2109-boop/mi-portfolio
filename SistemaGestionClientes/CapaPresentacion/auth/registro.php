<?php
require_once __DIR__ . '/../../CapaNegocio/UsuarioNegocio.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $nombre = $data['nombre'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $usuarioNegocio = new UsuarioNegocio();
        $resultado = $usuarioNegocio->registrar($nombre, $email, $password);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente']);
        } else {
            throw new Exception('Error al registrar usuario');
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
