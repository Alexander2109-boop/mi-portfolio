<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../CapaEntidades/Usuario.php';
require_once __DIR__ . '/../CapaExcepciones/UsuarioNoExistenteException.php';
require_once __DIR__ . '/../CapaExcepciones/ContraseñaIncorrectaException.php';
require_once __DIR__ . '/../CapaExcepciones/CuentaBloqueadaException.php';

class UsuarioDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    public function autenticar($email, $password) {
        try {
            $sql = "CALL sp_usuario_autenticar(?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$email, $password]);
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new Usuario($row['id'], $row['nombre'], $row['email'], $row['password'], $row['rol_id'], $row['activo']);
            }
        } catch (PDOException $e) {
            $mensaje = $e->getMessage();
            
            // 1. Usuario No Existe
            if (strpos($mensaje, 'Usuario no encontrado') !== false) {
                throw new UsuarioNoExistenteException("El correo no existe.");
            }

            // 2. Cuenta Bloqueada (Enviamos email y fecha actual)
            if (strpos($mensaje, 'Cuenta bloqueada') !== false) {
                $fechaHoy = date('Y-m-d H:i:s');
                throw new CuentaBloqueadaException("Tu cuenta ha sido bloqueada por seguridad.", $email, $fechaHoy);
            }

            // 3. Clave Incorrecta (Extraemos intentos si el SP los devuelve, o enviamos un valor)
            if (strpos($mensaje, 'Clave incorrecta') !== false) {
                // Suponiendo que tu SP maneja 3 intentos, aquí podrías calcular o recibir los restantes
                // Por ahora enviamos un mensaje genérico con lógica de intentos
                throw new ContraseñaIncorrectaException("La contraseña es incorrecta.", 1); 
            }
            
            throw new Exception("Error del sistema: " . $mensaje);
        }
        return null;
    }
    /**
     * Registra un nuevo usuario usando sp_usuario_registrar
     */
    public function registrar(Usuario $usuario) {
        $sql = "CALL sp_usuario_registrar(?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            $usuario->getNombre(),
            $usuario->getEmail(),
            $usuario->getPassword(),
            $usuario->getRolId()
        ]);
    }

    /**
     * Lista todos los usuarios
     */
    public function listar() {
    $sql = "CALL sp_usuario_listar()";
    $stmt = $this->conexion->query($sql);
    $usuarios = []; // Se crea la lista vacía
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $usuarios[] = new Usuario(
            $row['id'], $row['nombre'], $row['email'], 
            $row['password'], $row['rol_id'], $row['activo']
        );
    }
    return $usuarios; // ESTA LÍNEA ES CLAVE
}

    /**
     * Obtiene un usuario por ID
     */
    public function obtenerPorId($id) {
        $sql = "CALL sp_usuario_obtener_uno(?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return new Usuario(
                $row['id'], $row['nombre'], $row['email'], 
                $row['password'], $row['rol_id'], $row['activo']
            );
        }
        return null;
    }

    /**
     * Desbloquea una cuenta usando sp_usuario_desbloquear
     */
    public function desbloquearCuenta($id) {
        $sql = "CALL sp_usuario_desbloquear(?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }
}