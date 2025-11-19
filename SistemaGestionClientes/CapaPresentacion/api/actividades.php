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
            $actividad = $actividadNegocio->obtenerPorId($_GET['id']);
            echo json_encode($actividad);
        } else {
            $actividades = $actividadNegocio->listar();
            echo json_encode($actividades);
        }
    }
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $resultado = $actividadNegocio->crear(
            $data['oportunidad_id'],
            $data['tipo_actividad_id'],
            $data['fecha_hora'],
            $data['descripcion']
        );
        echo json_encode(['success' => $resultado]);
    }
    elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $resultado = $actividadNegocio->actualizar(
            $data['id'],
            $data['oportunidad_id'],
            $data['tipo_actividad_id'],
            $data['fecha_hora'],
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
