<?php
require_once __DIR__ . '/../../CapaNegocio/ActividadNegocio.php';

session_start();

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json');
$actividadNegocio = new ActividadNegocio();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            echo json_encode($actividadNegocio->obtenerPorId($_GET['id']));
        } else {
            echo json_encode($actividadNegocio->listar());
        }
    }

    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        // CORRECCIÓN: Se eliminó fecha_hora porque el SP usa NOW()
        $resultado = $actividadNegocio->crear(
            $data['oportunidad_id'],
            $data['tipo_actividad_id'],
            $data['descripcion']
        );
        echo json_encode(['success' => $resultado]);
    }

    elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        // CORRECCIÓN: Según tu Negocio/DAO, actualizar solo pide ID y descripción
        $resultado = $actividadNegocio->actualizar(
            $data['id'],
            $data['descripcion']
        );
        echo json_encode(['success' => $resultado]);
    }

    elseif ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;
        $resultado = $actividadNegocio->eliminar($id);
        echo json_encode(['success' => $resultado]);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}