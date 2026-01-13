<?php
require_once __DIR__ . '/../../CapaNegocio/DocumentoNegocio.php';

session_start();

// Verificación de seguridad
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json');
$documentoNegocio = new DocumentoNegocio();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    // MÉTODO GET: Listar o consultar por ID
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $documento = $documentoNegocio->obtenerPorId($_GET['id']);
            echo json_encode($documento);
        } else {
            $documentos = $documentoNegocio->listar();
            echo json_encode($documentos);
        }
    }

    // MÉTODO POST: Crear nuevo documento
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        // Validamos que los datos mínimos existan en el JSON
        $resultado = $documentoNegocio->crear(
            $data['oportunidad_id'] ?? null,
            $data['nombre'] ?? null,
            $data['url'] ?? null,
            $data['tipo'] ?? 'Otro' // Si no viene tipo, le ponemos 'Otro'
        );

        echo json_encode(['success' => $resultado]);
    }

    // MÉTODO PUT: Actualizar documento
    elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);

        $resultado = $documentoNegocio->actualizar(
            $data['id'] ?? null,
            $data['oportunidad_id'] ?? null,
            $data['nombre'] ?? null,
            $data['url'] ?? null,
            $data['tipo'] ?? 'Otro'
        );

        echo json_encode(['success' => $resultado]);
    }

    // MÉTODO DELETE: Eliminar documento
    elseif ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;
        $resultado = $documentoNegocio->eliminar($id);
        echo json_encode(['success' => $resultado]);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}