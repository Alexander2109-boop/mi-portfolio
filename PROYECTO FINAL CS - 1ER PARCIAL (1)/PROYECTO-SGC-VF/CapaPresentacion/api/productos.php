<?php
require_once __DIR__ . '/../../CapaNegocio/ProductoNegocio.php';  // Importa la capa de negocio para manejar productos
session_start(); // Inicia la sesión para verificar usuario autenticado

// Verifica si el usuario está autenticado mediante la variable de sesión
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401); // Devuelve código HTTP 401 (No autorizado)
    echo json_encode(['error' => 'No autorizado']); // Mensaje JSON de error
    exit; // Detiene la ejecución del script
}

header('Content-Type: application/json'); // Especifica que la respuesta será JSON
$productoNegocio = new ProductoNegocio(); // Instancia la clase de negocio para productos

try {
    $method = $_SERVER['REQUEST_METHOD']; // Obtiene el método HTTP utilizado
    
    if ($method === 'GET') { // Si es una solicitud GET
        if (isset($_GET['id'])) { // Si se envía un ID, obtiene un producto específico
            $producto = $productoNegocio->obtenerPorId($_GET['id']);
            echo json_encode($producto); // Devuelve el producto en formato JSON
        } else {
            $productos = $productoNegocio->listar(); // Lista todos los productos
            echo json_encode($productos); // Devuelve la lista en JSON
        }
    }
    elseif ($method === 'POST') { // Si es una solicitud POST (crear producto)
        $data = json_decode(file_get_contents('php://input'), true); // Obtiene el JSON enviado en el body
        $resultado = $productoNegocio->crear(
            $data['nombre'],
            $data['descripcion'],
            $data['precio']
        );
        echo json_encode(['success' => $resultado]); // Devuelve si la creación fue exitosa
    }
    elseif ($method === 'PUT') { // Si es una solicitud PUT (actualizar producto)
        $data = json_decode(file_get_contents('php://input'), true); // Decodifica datos enviados
        $resultado = $productoNegocio->actualizar(
            $data['id'],
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['activo']
        );
        echo json_encode(['success' => $resultado]); // Devuelve si la actualización fue exitosa
    }
    elseif ($method === 'DELETE') { // Si es una solicitud DELETE (eliminar producto)
        $id = $_GET['id'] ?? null; // Obtiene el ID del producto a eliminar
        $resultado = $productoNegocio->eliminar($id);
        echo json_encode(['success' => $resultado]); // Devuelve si la eliminación fue exitosa
    }
} catch (Exception $e) { // Captura cualquier excepción
    http_response_code(400); // Código HTTP 400 (Error en la solicitud)
    echo json_encode(['success' => false, 'error' => $e->getMessage()]); // Devuelve el error en JSON
}
