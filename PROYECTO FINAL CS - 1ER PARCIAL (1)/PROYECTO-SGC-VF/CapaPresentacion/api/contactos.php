<?php
// Se importa la capa de negocio que contiene la lógica para manejar contactos
require_once __DIR__ . '/../../CapaNegocio/ContactoNegocio.php';

// Se inicia la sesión para validar al usuario
session_start();

// Verifica si el usuario está autenticado mediante la variable de sesión
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401); // 401 = No autorizado
    echo json_encode(['error' => 'No autorizado']);
    exit; // Se detiene la ejecución del script
}

// Se especifica que la respuesta será en formato JSON
header('Content-Type: application/json');

// Se instancia la clase de negocio encargada de gestionar contactos
$contactoNegocio = new ContactoNegocio();

try {
    // Obtiene el método HTTP utilizado (GET, POST, PUT o DELETE)
    $method = $_SERVER['REQUEST_METHOD'];
    
    // ----- MANEJO DE PETICIONES GET -----
    if ($method === 'GET') {

        // Si se envía un ID por URL, se obtiene un solo contacto
        if (isset($_GET['id'])) {
            $contacto = $contactoNegocio->obtenerPorId($_GET['id']);
            echo json_encode($contacto);
        } 
        // Si no se envía ID, se listan todos los contactos
        else {
            $contactos = $contactoNegocio->listar();
            echo json_encode($contactos);
        }
    }

    // ----- MANEJO DE PETICIONES POST -----
    elseif ($method === 'POST') {

        // Se obtiene el cuerpo JSON enviado y se convierte en un array asociativo
        $data = json_decode(file_get_contents('php://input'), true);

        // Llama al método crear enviando los parámetros correspondientes
        $resultado = $contactoNegocio->crear(
            $data['cliente_id'],
            $data['nombre'],
            $data['cargo'],
            $data['email'],
            $data['telefono']
        );

        // Retorna el resultado de la operación
        echo json_encode(['success' => $resultado]);
    }

    // ----- MANEJO DE PETICIONES PUT -----
    elseif ($method === 'PUT') {

        // Se obtiene y decodifica el cuerpo de la petición
        $data = json_decode(file_get_contents('php://input'), true);

        // Se llama al método actualizar con los valores enviados
        $resultado = $contactoNegocio->actualizar(
            $data['id'],
            $data['cliente_id'],
            $data['nombre'],
            $data['cargo'],
            $data['email'],
            $data['telefono']
        );

        // Se retorna el estado de la actualización
        echo json_encode(['success' => $resultado]);
    }

    // ----- MANEJO DE PETICIONES DELETE -----
    elseif ($method === 'DELETE') {

        // Obtiene el ID del contacto a eliminar desde los parámetros GET
        $id = $_GET['id'] ?? null;

        // Llama al método eliminar en la capa de negocio
        $resultado = $contactoNegocio->eliminar($id);

        // Envía la respuesta con el resultado
        echo json_encode(['success' => $resultado]);
    }

} catch (Exception $e) {

    // Si ocurre un error, se devuelve un código 400 y el mensaje de la excepción
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
