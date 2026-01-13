<?php
require_once __DIR__ . '/../CapaDatos/UsuarioDAO.php';
require_once __DIR__ . '/../CapaEntidades/Usuario.php';

/**
 * Capa de Negocio para Usuario
 */
class UsuarioNegocio {
    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }

    /**
     * Autentica un usuario.
     * Deja pasar las excepciones específicas (Bloqueo, Clave incorrecta) al controlador.
     */
    public function autenticar($email, $password) {
        // Validación de formato
        if (empty($email) || empty($password)) {
            throw new Exception("El correo y la contraseña son obligatorios.");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El formato del correo no es válido.");
        }

        // El DAO ya maneja sp_usuario_autenticar y lanza sus propias excepciones
        // No es necesario atraparlas aquí si queremos que el controlador las reciba
        $usuario = $this->usuarioDAO->autenticar($email, $password);

        if (!$usuario) {
            throw new Exception("No se pudo iniciar sesión.");
        }

        return $usuario;
    }

    /**
     * Registra un nuevo usuario.
     */
    public function registrar($nombre, $email, $password, $rol_id = 2) {
        if (empty($nombre) || empty($email) || empty($password)) {
            throw new Exception("Todos los campos de registro son obligatorios.");
        }

        if (strlen($password) < 6) {
            throw new Exception("La contraseña es muy corta (mínimo 6 caracteres).");
        }

        // Crear la entidad
        $usuario = new Usuario(
            null, 
            $nombre,
            $email,
            $password,
            $rol_id,
            1 // activo por defecto
        );

        return $this->usuarioDAO->registrar($usuario);
    }

    /**
     * Listar todos los usuarios.
     */
  public function listar() {
        $lista = $this->usuarioDAO->listar();
        if (!$lista) {
            return []; // Devolvemos un array vacío en lugar de nada
        }
        return $lista;
    }

    /**
     * Obtener un usuario por ID.
     */
    public function obtenerPorId($id) {
        if (empty($id)) {
            throw new Exception("ID de usuario no válido.");
        }
        return $this->usuarioDAO->obtenerPorId($id);
    }

    /**
     * Desbloquear una cuenta (Uso administrativo).
     */
    public function desbloquear($id) {
        if (empty($id)) {
            throw new Exception("ID requerido para desbloquear.");
        }
        return $this->usuarioDAO->desbloquearCuenta($id);
    }
}