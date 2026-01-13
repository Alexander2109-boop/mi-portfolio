<?php
session_start();
header('Content-Type: application/json');

// Importamos la Capa de Negocio y las Excepciones
require_once __DIR__ . '/../../CapaNegocio/UsuarioNegocio.php';
require_once __DIR__ . '/../../CapaExcepciones/UsuarioNoExistenteException.php';
require_once __DIR__ . '/../../CapaExcepciones/ContraseñaIncorrectaException.php';
require_once __DIR__ . '/../../CapaExcepciones/CuentaBloqueadaException.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Email y contraseña son requeridos'
        ]);
        exit;
    }

    try {
        // CORRECCIÓN: Usamos la Capa de Negocio en lugar del DAO directamente
        $usuarioNegocio = new UsuarioNegocio();
        $usuario = $usuarioNegocio->autenticar($email, $password);

        // Si la autenticación es exitosa, creamos la sesión
        $_SESSION['usuario_id'] = $usuario->getId();
        $_SESSION['usuario_nombre'] = $usuario->getNombre();
        $_SESSION['usuario_email'] = $usuario->getEmail();
        $_SESSION['usuario_rol'] = $usuario->getRolId();

        echo json_encode([
            'success' => true,
            'message' => 'Login exitoso',
            'usuario' => [
                'nombre' => $usuario->getNombre(),
                'rol' => $usuario->getRolId()
            ]
        ]);

    } catch (UsuarioNoExistenteException $e) {
        echo json_encode([
            'success' => false,
            'tipo_error' => 'USUARIO_NO_EXISTENTE',
            'message' => 'El correo ingresado no existe.'
        ]);
    } catch (CuentaBloqueadaException $e) {
        echo json_encode([
            'success' => false,
            'tipo_error' => 'CUENTA_BLOQUEADA',
            'message' => $e->getMessage(),
            'detalles' => 'Bloqueado el: ' . $e->getFechaBloqueo()
        ]);
    } catch (ContraseñaIncorrectaException $e) {
        echo json_encode([
            'success' => false,
            'tipo_error' => 'CONTRASEÑA_INCORRECTA',
            'message' => $e->getMessage(),
            'intentos_restantes' => $e->getIntentosRestantes()
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error inesperado en el servidor',
            'error' => $e->getMessage()
        ]);
    }
    
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Método no permitido']);
}