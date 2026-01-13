<?php
/**
 * API REST - clientes.php
 * Descripción: Endpoint REST para operaciones CRUD de Clientes
 * Métodos HTTP soportados: GET, POST, PUT, DELETE
 * Seguridad: Requiere sesión activa
 */

// Importar capa de negocio (que valida y usa el DAO)
require_once __DIR__ . '/../../CapaNegocio/ClienteNegocio.php';

// Iniciar sesión para verificar autenticación
session_start();

// ========================================
// VERIFICACIÓN DE AUTENTICACIÓN
// ========================================
if (!isset($_SESSION['usuario_id'])) {
    // Si no hay sesión, rechazar con código 401 Unauthorized
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Configurar respuesta como JSON
header('Content-Type: application/json');

// Instanciar la capa de negocio
$clienteNegocio = new ClienteNegocio();

// BLOQUE TRY-CATCH para manejo global de errores
try {
    // Obtener el método HTTP de la petición
    $method = $_SERVER['REQUEST_METHOD'];
    
    // ========================================
    // MÉTODO GET: Listar o Obtener por ID
    // ========================================
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            // GET con ID: Obtener un cliente específico
            $cliente = $clienteNegocio->obtenerPorId($_GET['id']);
            echo json_encode($cliente);
        } else {
            // GET sin ID: Listar todos los clientes
            $clientes = $clienteNegocio->listar();
            echo json_encode($clientes);
        }
    }
    
    // ========================================
    // MÉTODO POST: Crear nuevo cliente
    // ========================================
    elseif ($method === 'POST') {
        // Leer datos JSON del cuerpo de la petición
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Llamar a la capa de negocio para validar y crear
        $resultado = $clienteNegocio->crear(
            $data['nombre'],
            $data['tipo_cliente_id'],
            $data['telefono'],
            $data['direccion'],
            $data['fecha_alta']
        );
        
        echo json_encode(['success' => $resultado]);
    }
    
    // ========================================
    // MÉTODO PUT: Actualizar cliente existente
    // ========================================
    elseif ($method === 'PUT') {
        // Leer datos JSON del cuerpo
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Llamar a la capa de negocio para validar y actualizar
        $resultado = $clienteNegocio->actualizar(
            $data['id'],
            $data['nombre'],
            $data['tipo_cliente_id'],
            $data['telefono'],
            $data['direccion'],
            $data['fecha_alta'],
            $data['activo']
        );
        
        echo json_encode(['success' => $resultado]);
    }
    
    // ========================================
    // MÉTODO DELETE: Eliminar cliente
    // ========================================
    elseif ($method === 'DELETE') {
        // Obtener ID desde query string
        $id = $_GET['id'] ?? null;
        
        // Eliminar a través de la capa de negocio
        $resultado = $clienteNegocio->eliminar($id);
        echo json_encode(['success' => $resultado]);
    }
    
} catch (Exception $e) {
    // ========================================
    // MANEJO GLOBAL DE ERRORES
    // ========================================
    // Si ocurre cualquier error, responder con código 400 Bad Request
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
}
