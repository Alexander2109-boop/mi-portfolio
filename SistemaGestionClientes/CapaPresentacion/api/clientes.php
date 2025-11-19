<?php
require_once __DIR__ . '/../../CapaNegocio/ClienteNegocio.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json');
$clienteNegocio = new ClienteNegocio();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $cliente = $clienteNegocio->obtenerPorId($_GET['id']);
            echo json_encode($cliente);
        } else {
            $clientes = $clienteNegocio->listar();
            echo json_encode($clientes);
        }
    }
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $resultado = $clienteNegocio->crear(
            $data['nombre'],
            $data['tipo_cliente_id'],
            $data['telefono'],
            $data['direccion'],
            $data['fecha_alta']
        );
        echo json_encode(['success' => $resultado]);
    }
    elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $resultado = $clienteNegocio->actualizar(
            $data['id'],
            $data['nombre'],
            $data['tipo_cliente_id'],
            $data['telefono'],
            $data['direccion'],
            $data['fecha_alta'],
            $data['activo']
        );
        echo json_encode(['success' => $resultado]);
    }
    elseif ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;
        $resultado = $clienteNegocio->eliminar($id);
        echo json_encode(['success' => $resultado]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
