<?php
require_once __DIR__ . '/../../CapaNegocio/DocumentoNegocio.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json');
$documentoNegocio = new DocumentoNegocio();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $documento = $documentoNegocio->obtenerPorId($_GET['id']);
            echo json_encode($documento);
        } else {
            $documentos = $documentoNegocio->listar();
            echo json_encode($documentos);
        }
    }
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $resultado = $documentoNegocio->crear(
            $data['oportunidad_id'],
            $data['nombre'],
            $data['url'],
            $data['tipo']
        );
        echo json_encode(['success' => $resultado]);
    }
    elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $resultado = $documentoNegocio->actualizar(
            $data['id'],
            $data['oportunidad_id'],
            $data['nombre'],
            $data['url'],
            $data['tipo']
        );
        echo json_encode(['success' => $resultado]);
    }
    elseif ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;
        $resultado = $documentoNegocio->eliminar($id);
        echo json_encode(['success' => $resultado]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
