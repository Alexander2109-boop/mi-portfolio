<?php
// Importar la capa de negocio responsable de manejar las actividades
require_once __DIR__ . '/../../CapaNegocio/ActividadNegocio.php';

// Iniciar sesión para validar autenticación del usuario
session_start();

// Verificar si el usuario tiene sesión activa
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401); // Código 401 = No autorizado
    echo json_encode(['error' => 'No autorizado']);
    exit; // Detener ejecución del script
}

// Configurar el tipo de respuesta como JSON
header('Content-Type: application/json');

// Instanciar la clase de negocio para gestionar actividades
$actividadNegocio = new ActividadNegocio();

try {
    // Obtener el método HTTP utilizado en la solicitud (GET, POST, PUT, DELETE)
    $method = $_SERVER['REQUEST_METHOD'];
    
    // ==========================================
    // MÉTODO GET: Listar o consultar actividad
    // ==========================================
    if ($method === 'GET') {

        // Si se envía un ID, devolver una sola actividad
        if (isset($_GET['id'])) {
            $actividad = $actividadNegocio->obtenerPorId($_GET['id']);
            echo json_encode($actividad);
        } 
        // Si no se envía ID, devolver todas las actividades
        else {
            $actividades = $actividadNegocio->listar();
            echo json_encode($actividades);
        }
    }

    // ==========================================
    // MÉTODO POST: Crear nueva actividad
    // ==========================================
    elseif ($method === 'POST') {

        // Leer el cuerpo de la petición (JSON) y convertirlo en array asociativo
        $data = json_decode(file_get_contents('php://input'), true);

        // Enviar los datos a la capa de negocio para creación
        $resultado = $actividadNegocio->crear(
            $data['oportunidad_id'],
            $data['tipo_actividad_id'],
            $data['fecha_hora'],
            $data['descripcion']
        );

        // Retornar respuesta de éxito o fallo
        echo json_encode(['success' => $resultado]);
    }

    // ==========================================
    // MÉTODO PUT: Actualizar actividad existente
    // ==========================================
    elseif ($method === 'PUT') {

        // Leer y decodificar el cuerpo JSON de la petición
        $data = json_decode(file_get_contents('php://input'), true);

        // Actualizar usando los valores enviados
        $resultado = $actividadNegocio->actualizar(
            $data['id'],
            $data['oportunidad_id'],
            $data['tipo_actividad_id'],
            $data['fecha_hora'],
            $data['descripcion']
        );

        // Enviar respuesta
        echo json_encode(['success' => $resultado]);
    }

    // ==========================================
    // MÉTODO DELETE: Eliminar actividad
    // ==========================================
    elseif ($method === 'DELETE') {

        // Obtener ID desde la URL (query string)
        $id = $_GET['id'] ?? null;

        // Solicitar eliminación a la capa de negocio
        $resultado = $actividadNegocio->eliminar($id);

        // Enviar respuesta
        echo json_encode(['success' => $resultado]);
    }

} catch (Exception $e) {

    // ======================================================
    // MANEJO GLOBAL DE ERRORES: cualquier excepción capturada
    // ======================================================
    http_response_code(400); // 400 = Bad Request
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage() // Retornar el mensaje de error
    ]);
}
