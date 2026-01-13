<?php
// Se incluye la clase DocumentoNegocio, que contiene la lógica de negocio para manejar documentos
require_once __DIR__ . '/../../CapaNegocio/DocumentoNegocio.php';

// Se inicia la sesión para poder verificar si el usuario está autenticado
session_start();

// Si no existe la variable de sesión 'usuario_id', se bloquea el acceso
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401); // Código HTTP 401: No autorizado
    echo json_encode(['error' => 'No autorizado']);
    exit; // Se detiene la ejecución del script
}

// Indica que todas las respuestas serán en formato JSON
header('Content-Type: application/json');

// Se instancia la capa de negocio responsable de manejar documentos
$documentoNegocio = new DocumentoNegocio();

try {
    // Se obtiene el método HTTP utilizado en la petición (GET, POST, PUT, DELETE)
    $method = $_SERVER['REQUEST_METHOD'];
    
    // ----- PETICIONES GET -----
    if ($method === 'GET') {
        // Si se envía un ID por parámetro, se obtiene un solo documento
        if (isset($_GET['id'])) {
            $documento = $documentoNegocio->obtenerPorId($_GET['id']);
            echo json_encode($documento);
        } else {
            // Si no hay ID, se listan todos los documentos
            $documentos = $documentoNegocio->listar();
            echo json_encode($documentos);
        }
    }

    // ----- PETICIONES POST -----
    elseif ($method === 'POST') {
        // Se obtiene el cuerpo RAW de la petición y se decodifica desde JSON a array
        $data = json_decode(file_get_contents('php://input'), true);

        // Se envían los datos al método crear de la clase negocio
        $resultado = $documentoNegocio->crear(
            $data['oportunidad_id'],
            $data['nombre'],
            $data['url'],
            $data['tipo']
        );

        // Se responde si la creación fue exitosa
        echo json_encode(['success' => $resultado]);
    }

    // ----- PETICIONES PUT -----
    elseif ($method === 'PUT') {
        // Se obtiene el JSON enviado y se convierte en array
        $data = json_decode(file_get_contents('php://input'), true);

        // Se llama al método actualizar pasando los valores recibidos
        $resultado = $documentoNegocio->actualizar(
            $data['id'],
            $data['oportunidad_id'],
            $data['nombre'],
            $data['url'],
            $data['tipo']
        );

        // Se responde el resultado de la operación
        echo json_encode(['success' => $resultado]);
    }

    // ----- PETICIONES DELETE -----
    elseif ($method === 'DELETE') {
        // Se obtiene el ID desde los parámetros GET
        $id = $_GET['id'] ?? null;

        // Se intenta eliminar el documento
        $resultado = $documentoNegocio->eliminar($id);

        // Se envía la respuesta
        echo json_encode(['success' => $resultado]);
    }

} catch (Exception $e) {
    // Si ocurre alguna excepción, se devuelve un error con código HTTP 400
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
