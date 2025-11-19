<?php
require_once __DIR__ . '/../../CapaNegocio/ProductoNegocio.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json');
$productoNegocio = new ProductoNegocio();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $producto = $productoNegocio->obtenerPorId($_GET['id']);
            echo json_encode($producto);
        } else {
            $productos = $productoNegocio->listar();
            echo json_encode($productos);
        }
    }
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $resultado = $productoNegocio->crear(
            $data['nombre'],
            $data['descripcion'],
            $data['precio']
        );
        echo json_encode(['success' => $resultado]);
    }
    elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $resultado = $productoNegocio->actualizar(
            $data['id'],
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['activo']
        );
        echo json_encode(['success' => $resultado]);
    }
    elseif ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;
        $resultado = $productoNegocio->eliminar($id);
        echo json_encode(['success' => $resultado]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
