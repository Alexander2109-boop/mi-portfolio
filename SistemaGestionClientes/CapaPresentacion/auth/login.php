<?php
require_once __DIR__ . '/../../CapaNegocio/UsuarioNegocio.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $usuarioNegocio = new UsuarioNegocio();
        $usuario = $usuarioNegocio->autenticar($email, $password);

        $_SESSION['usuario_id'] = $usuario->getId();
        $_SESSION['usuario_nombre'] = $usuario->getNombre();
        $_SESSION['usuario_email'] = $usuario->getEmail();
        $_SESSION['usuario_rol'] = $usuario->getRolId();

        echo json_encode(['success' => true, 'nombre' => $usuario->getNombre()]);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
