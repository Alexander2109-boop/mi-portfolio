<?php
require_once __DIR__ . '/../../CapaNegocio/ClienteNegocio.php';

session_start();

// Verificación de seguridad
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json');
$clienteNegocio = new ClienteNegocio();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    // MÉTODO GET: Listar o Obtener por ID
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $cliente = $clienteNegocio->obtenerPorId($_GET['id']);
            echo json_encode($cliente);
        } else {
            $clientes = $clienteNegocio->listar();
            echo json_encode($clientes);
        }
    }
    
    // MÉTODO POST: Crear nuevo cliente
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $resultado = $clienteNegocio->crear(
            $data['nombre'],
            $data['tipo_cliente_id'],
            $data['telefono'] ?? null,
            $data['direccion'] ?? null,
            $data['fecha_alta'] ?? date('Y-m-d') // Si no viene fecha, usa la de hoy
        );
        
        echo json_encode(['success' => $resultado]);
    }
    
    // MÉTODO PUT: Actualizar cliente existente
    elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $resultado = $clienteNegocio->actualizar(
            $data['id'],
            $data['nombre'],
            $data['tipo_cliente_id'],
            $data['telefono'] ?? null,
            $data['direccion'] ?? null,
            $data['fecha_alta'] ?? null,
            $data['activo'] ?? 1
        );
        
        echo json_encode(['success' => $resultado]);
    }
    
    // MÉTODO DELETE: Eliminar cliente
    elseif ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;
        $resultado = $clienteNegocio->eliminar($id);
        echo json_encode(['success' => $resultado]);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
}