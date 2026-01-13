<?php
require_once __DIR__ . '/../../CapaNegocio/OportunidadNegocio.php';

session_start();

// Verificación de seguridad
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json');
$oportunidadNegocio = new OportunidadNegocio();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    // MÉTODO GET: Listar o consultar por ID
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            echo json_encode($oportunidadNegocio->obtenerPorId($_GET['id']));
        } else {
            echo json_encode($oportunidadNegocio->listar());
        }
    }
    
    // MÉTODO POST: Crear nueva oportunidad
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $resultado = $oportunidadNegocio->crear(
            $data['cliente_id'],
            $data['estado_oportunidad_id'],
            $data['fecha_hora'] ?? date('Y-m-d H:i:s'), // Fecha actual si no se envía
            $data['monto'] ?? 0,
            $data['descripcion'] ?? null
        );

        echo json_encode(['success' => $resultado]);
    }

    // MÉTODO PUT: Actualizar oportunidad
    elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);

        $resultado = $oportunidadNegocio->actualizar(
            $data['id'],
            $data['cliente_id'],
            $data['estado_oportunidad_id'],
            $data['fecha_hora'] ?? null,
            $data['monto'] ?? 0,
            $data['descripcion'] ?? null
        );

        echo json_encode(['success' => $resultado]);
    }

    // MÉTODO DELETE: Eliminar oportunidad
    elseif ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;
        $resultado = $oportunidadNegocio->eliminar($id);
        echo json_encode(['success' => $resultado]);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}