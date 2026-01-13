<?php
require_once __DIR__ . '/../../CapaNegocio/ProductoNegocio.php';

session_start();

// Verificación de seguridad
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json');
$productoNegocio = new ProductoNegocio();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    // MÉTODO GET: Listar o consultar por ID
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $producto = $productoNegocio->obtenerPorId($_GET['id']);
            echo json_encode($producto);
        } else {
            $productos = $productoNegocio->listar();
            echo json_encode($productos);
        }
    }
    
    // MÉTODO POST: Crear nuevo producto
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $resultado = $productoNegocio->crear(
            $data['nombre'] ?? null,
            $data['descripcion'] ?? null,
            $data['precio'] ?? 0
        );
        
        echo json_encode(['success' => $resultado]);
    }
    
    // MÉTODO PUT: Actualizar producto existente
    elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $resultado = $productoNegocio->actualizar(
            $data['id'] ?? null,
            $data['nombre'] ?? null,
            $data['descripcion'] ?? null,
            $data['precio'] ?? 0,
            $data['activo'] ?? 1
        );
        
        echo json_encode(['success' => $resultado]);
    }
    
    // MÉTODO DELETE: Eliminar producto
    elseif ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;
        $resultado = $productoNegocio->eliminar($id);
        echo json_encode(['success' => $resultado]);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
}