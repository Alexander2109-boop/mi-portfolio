<?php
// Importar la capa de negocio responsable de manejar las oportunidades
require_once __DIR__ . '/../../CapaNegocio/OportunidadNegocio.php';

// Iniciar sesión para verificar autenticación del usuario
session_start();

// Validar si el usuario tiene sesión activa
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401); // 401 = No autorizado
    echo json_encode(['error' => 'No autorizado']);
    exit; // Detener ejecución
}

// La respuesta será enviada en formato JSON
header('Content-Type: application/json');

// Instanciar la clase de negocio que gestiona las oportunidades
$oportunidadNegocio = new OportunidadNegocio();

try {
    // Detectar el método HTTP usado en la solicitud (GET, POST, PUT, DELETE)
    $method = $_SERVER['REQUEST_METHOD'];
    
    // ================================================
    // MÉTODO GET: Consultar una oportunidad o listar todas
    // ================================================
    if ($method === 'GET') {

        // Si se envía un ID por parámetro, obtener una oportunidad específica
        if (isset($_GET['id'])) {
            $oportunidad = $oportunidadNegocio->obtenerPorId($_GET['id']);
            echo json_encode($oportunidad);
        } 
        // Si no se envía ID, listar todas las oportunidades
        else {
            $oportunidades = $oportunidadNegocio->listar();
            echo json_encode($oportunidades);
        }
    }
    
    // ================================================
    // MÉTODO POST: Crear nueva oportunidad
    // ================================================
    elseif ($method === 'POST') {

        // Leer el cuerpo de la solicitud (JSON) y convertirlo a array
        $data = json_decode(file_get_contents('php://input'), true);

        // Crear nueva oportunidad enviando los datos a la capa de negocio
        $resultado = $oportunidadNegocio->crear(
            $data['cliente_id'],
            $data['estado_oportunidad_id'],
            $data['fecha_hora'],
            $data['monto'],
            $data['descripcion']
        );

        // Devolver resultado de la operación
        echo json_encode(['success' => $resultado]);
    }

    // ================================================
    // MÉTODO PUT: Actualizar una oportunidad existente
    // ================================================
    elseif ($method === 'PUT') {

        // Leer el cuerpo JSON de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);

        // Actualizar los datos de la oportunidad
        $resultado = $oportunidadNegocio->actualizar(
            $data['id'],
            $data['cliente_id'],
            $data['estado_oportunidad_id'],
            $data['fecha_hora'],
            $data['monto'],
            $data['descripcion']
        );

        // Devolver estado del proceso
        echo json_encode(['success' => $resultado]);
    }

    // ================================================
    // MÉTODO DELETE: Eliminar una oportunidad
    // ================================================
    elseif ($method === 'DELETE') {

        // Obtener el ID desde la URL (query string)
        $id = $_GET['id'] ?? null;

        // Solicitar eliminación a la capa de negocio
        $resultado = $oportunidadNegocio->eliminar($id);

        // Responder con el éxito o fallo
        echo json_encode(['success' => $resultado]);
    }

} catch (Exception $e) {

    // ====================================================
    // MANEJO GLOBAL DE ERRORES: cualquier excepción atrapada
    // ====================================================
    http_response_code(400); // 400 = Bad Request
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage() // Enviar mensaje de error
    ]);
}
