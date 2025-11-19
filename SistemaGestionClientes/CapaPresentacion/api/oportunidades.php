<?php
require_once __DIR__ . '/../../CapaNegocio/OportunidadNegocio.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json');
$oportunidadNegocio = new OportunidadNegocio();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $oportunidad = $oportunidadNegocio->obtenerPorId($_GET['id']);
            echo json_encode($oportunidad);
        } else {
            $oportunidades = $oportunidadNegocio->listar();
            echo json_encode($oportunidades);
        }
    }
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $resultado = $oportunidadNegocio->crear(
            $data['cliente_id'],
            $data['estado_oportunidad_id'],
            $data['fecha_hora'],
            $data['monto'],
            $data['descripcion']
        );
        echo json_encode(['success' => $resultado]);
    }
    elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $resultado = $oportunidadNegocio->actualizar(
            $data['id'],
            $data['cliente_id'],
            $data['estado_oportunidad_id'],
            $data['fecha_hora'],
            $data['monto'],
            $data['descripcion']
        );
        echo json_encode(['success' => $resultado]);
    }
    elseif ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;
        $resultado = $oportunidadNegocio->eliminar($id);
        echo json_encode(['success' => $resultado]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
