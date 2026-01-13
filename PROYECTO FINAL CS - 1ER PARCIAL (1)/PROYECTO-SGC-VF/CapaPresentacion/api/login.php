<?php
// Iniciar sesión PHP para mantener estado de usuario autenticado
session_start();

// Configurar respuesta como JSON para comunicación con frontend
header('Content-Type: application/json');

// Importar las clases necesarias
require_once __DIR__ . '/../../CapaDatos/UsuarioDAO.php';
require_once __DIR__ . '/../../CapaExcepciones/UsuarioNoExistenteException.php';
require_once __DIR__ . '/../../CapaExcepciones/ContraseñaIncorrectaException.php';
require_once __DIR__ . '/../../CapaExcepciones/CuentaBloqueadaException.php';

/**
 * API DE AUTENTICACIÓN - login.php
 * 
 * Descripción: Endpoint REST para autenticación de usuarios
 * Método: POST
 * Entrada: JSON con {email, password}
 * Salida: JSON con {success, message, datos adicionales}
 * 
 * MANEJO DE EXCEPCIONES:
 * Este archivo demuestra la CAPTURA de las tres excepciones personalizadas:
 * 1. UsuarioNoExistenteException - Email no registrado
 * 2. ContraseñaIncorrectaException - Contraseña incorrecta con contador
 * 3. CuentaBloqueadaException - Cuenta bloqueada por 3 intentos fallidos
 */

// Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer y decodificar el JSON del cuerpo de la petición
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Extraer credenciales del JSON
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    // Validación básica de campos requeridos
    if (empty($email) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Email y contraseña son requeridos'
        ]);
        exit;
    }

    // BLOQUE TRY-CATCH: Manejo estructurado de excepciones
    try {
        // ========================================
        // PASO 1: INTENTAR AUTENTICAR
        // ========================================
        // Aquí es donde se LANZAN las excepciones desde UsuarioDAO
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->autenticar($email, $password);

        // Si llegamos aquí, significa que NO se lanzó ninguna excepción
        // Por lo tanto, el login fue EXITOSO
        
        // Guardar datos del usuario en la sesión PHP
        $_SESSION['usuario_id'] = $usuario->getId();
        $_SESSION['usuario_nombre'] = $usuario->getNombre();
        $_SESSION['usuario_email'] = $usuario->getEmail();
        $_SESSION['usuario_rol'] = $usuario->getRolId();

        // Responder con éxito
        echo json_encode([
            'success' => true,
            'message' => 'Login exitoso',
            'usuario' => [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'email' => $usuario->getEmail(),
                'rol' => $usuario->getRolId()
            ]
        ]);

    } 
    // ========================================
    // CAPTURA 1: Usuario No Existente
    // ========================================
    catch (UsuarioNoExistenteException $e) {
        // Esta excepción se lanza cuando el email no está registrado
        echo json_encode([
            'success' => false,
            'tipo_error' => 'USUARIO_NO_EXISTENTE',
            'message' => $e->getMessage(),
            'detalles' => 'El email ingresado no está registrado en el sistema.'
        ]);
    } 
    // ========================================
    // CAPTURA 2: Cuenta Bloqueada
    // ========================================
    catch (CuentaBloqueadaException $e) {
        // Se lanza cuando ya se bloqueó la cuenta (3 intentos fallidos)
        echo json_encode([
            'success' => false,
            'tipo_error' => 'CUENTA_BLOQUEADA',
            'message' => $e->getMessage(),
            'email' => $e->getEmail(),
            'fecha_bloqueo' => $e->getFechaBloqueo(),
            'detalles' => 'Su cuenta ha sido bloqueada por seguridad. Contacte al administrador para desbloquearla.'
        ]);
    } 
    // ========================================
    // CAPTURA 3: Contraseña Incorrecta
    // ========================================
    catch (ContraseñaIncorrectaException $e) {
        // Se lanza cuando la contraseña no coincide
        // Incluye información de intentos restantes
        echo json_encode([
            'success' => false,
            'tipo_error' => 'CONTRASEÑA_INCORRECTA',
            'message' => $e->getMessage(),
            'intentos_restantes' => $e->getIntentosRestantes(),
            'detalles' => 'La contraseña ingresada es incorrecta. Tiene ' . 
                         $e->getIntentosRestantes() . 
                         ' intentos restantes antes de que su cuenta sea bloqueada.'
        ]);
    } 
    // ========================================
    // CAPTURA 4: Excepciones No Previstas
    // ========================================
    catch (Exception $e) {
        // Captura cualquier otra excepción que no fue manejada específicamente
        echo json_encode([
            'success' => false,
            'tipo_error' => 'ERROR_INTERNO',
            'message' => 'Error interno del servidor',
            'detalles' => $e->getMessage()
        ]);
    }
    
} else {
    // Si no es POST, rechazar la petición
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido. Use POST.'
    ]);
}
