<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../CapaEntidades/Usuario.php';
require_once __DIR__ . '/../CapaExcepciones/UsuarioNoExistenteException.php';
require_once __DIR__ . '/../CapaExcepciones/ContraseñaIncorrectaException.php';
require_once __DIR__ . '/../CapaExcepciones/CuentaBloqueadaException.php';

/**
 * DAO para Usuario - Acceso a datos con manejo de excepciones
 */
class UsuarioDAO {
    private $conexion;
    private const MAX_INTENTOS = 3;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    /**
     * Autenticar usuario con manejo de excepciones y control de bloqueos
     * 
     * @throws UsuarioNoExistenteException Si el email no existe
     * @throws CuentaBloqueadaException Si la cuenta está bloqueada
     * @throws ContraseñaIncorrectaException Si la contraseña es incorrecta
     */
    public function autenticar($email, $password) {
        // Paso 1: Verificar si el usuario existe
        $usuario = $this->obtenerPorEmail($email);
        
        if (!$usuario) {
            // LANZAR EXCEPCIÓN: Usuario no existente
            throw new UsuarioNoExistenteException($email);
        }

        // Paso 2: Verificar si la cuenta está bloqueada
        if ($usuario['bloqueado'] == 1) {
            // LANZAR EXCEPCIÓN: Cuenta bloqueada
            throw new CuentaBloqueadaException($email, $usuario['fecha_bloqueo']);
        }

        // Paso 3: Verificar la contraseña
        if ($usuario['password'] !== $password) {
            // Incrementar intentos fallidos
            $this->incrementarIntentosFallidos($usuario['id']);
            
            // Verificar si se alcanzó el límite de intentos
            $intentosActuales = $usuario['intentos_fallidos'] + 1;
            
            if ($intentosActuales >= self::MAX_INTENTOS) {
                // Bloquear la cuenta
                $this->bloquearCuenta($usuario['id']);
                throw new CuentaBloqueadaException($email, date('Y-m-d H:i:s'));
            }
            
            $intentosRestantes = self::MAX_INTENTOS - $intentosActuales;
            
            // LANZAR EXCEPCIÓN: Contraseña incorrecta
            throw new ContraseñaIncorrectaException($intentosRestantes);
        }

        // Paso 4: Login exitoso - reiniciar contador de intentos
        $this->reiniciarIntentosFallidos($usuario['id']);

        // Retornar objeto Usuario
        return new Usuario(
            $usuario['id'],
            $usuario['nombre'],
            $usuario['email'],
            $usuario['password'],
            $usuario['rol_id'],
            $usuario['activo']
        );
    }

    /**
     * Obtener usuario por email (incluyendo campos de control)
     */
    private function obtenerPorEmail($email) {
        $sql = "SELECT * FROM Usuario WHERE email = :email";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Incrementar contador de intentos fallidos
     */
    private function incrementarIntentosFallidos($usuarioId) {
        $sql = "UPDATE Usuario SET intentos_fallidos = intentos_fallidos + 1, ultimo_intento = NOW() WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $usuarioId]);
    }

    /**
     * Bloquear cuenta de usuario
     */
    private function bloquearCuenta($usuarioId) {
        $sql = "UPDATE Usuario SET bloqueado = TRUE, fecha_bloqueo = NOW() WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $usuarioId]);
    }

    /**
     * Reiniciar contador de intentos fallidos (login exitoso)
     */
    private function reiniciarIntentosFallidos($usuarioId) {
        $sql = "UPDATE Usuario SET intentos_fallidos = 0, ultimo_intento = NULL WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $usuarioId]);
    }

    /**
     * Desbloquear cuenta (método administrativo)
     */
    public function desbloquearCuenta($usuarioId) {
        $sql = "UPDATE Usuario SET bloqueado = FALSE, intentos_fallidos = 0, fecha_bloqueo = NULL, ultimo_intento = NULL WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $usuarioId]);
    }

    /**
     * Registrar nuevo usuario - SIN ENCRIPTACIÓN
     */
    public function registrar(Usuario $usuario) {
        $sql = "INSERT INTO Usuario (nombre, email, password, rol_id, activo) VALUES (:nombre, :email, :password, :rol_id, :activo)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':nombre' => $usuario->getNombre(),
            ':email' => $usuario->getEmail(),
            ':password' => $usuario->getPassword(),
            ':rol_id' => $usuario->getRolId(),
            ':activo' => $usuario->getActivo()
        ]);
    }

    /**
     * Listar todos los usuarios
     */
    public function listar() {
        $sql = "SELECT * FROM Usuario ORDER BY nombre";
        $stmt = $this->conexion->query($sql);
        $usuarios = [];
        while ($row = $stmt->fetch()) {
            $usuarios[] = new Usuario(
                $row['id'],
                $row['nombre'],
                $row['email'],
                $row['password'],
                $row['rol_id'],
                $row['activo']
            );
        }
        return $usuarios;
    }

    /**
     * Obtener usuario por ID
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Usuario WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        
        if ($row) {
            return new Usuario(
                $row['id'],
                $row['nombre'],
                $row['email'],
                $row['password'],
                $row['rol_id'],
                $row['activo']
            );
        }
        return null;
    }
}
