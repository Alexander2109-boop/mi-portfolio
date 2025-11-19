<?php
require_once __DIR__ . '/../../CapaNegocio/ContactoNegocio.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json');
$contactoNegocio = new ContactoNegocio();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $contacto = $contactoNegocio->obtenerPorId($_GET['id']);
            echo json_encode($contacto);
        } else {
            $contactos = $contactoNegocio->listar();
            echo json_encode($contactos);
        }
    }
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $resultado = $contactoNegocio->crear(
            $data['cliente_id'],
            $data['nombre'],
            $data['cargo'],
            $data['email'],
            $data['telefono']
        );
        echo json_encode(['success' => $resultado]);
    }
    elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $resultado = $contactoNegocio->actualizar(
            $data['id'],
            $data['cliente_id'],
            $data['nombre'],
            $data['cargo'],
            $data['email'],
            $data['telefono']
        );
        echo json_encode(['success' => $resultado]);
    }
    elseif ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;
        $resultado = $contactoNegocio->eliminar($id);
        echo json_encode(['success' => $resultado]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
