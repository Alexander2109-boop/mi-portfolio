<?php
require_once __DIR__ . '/../CapaDatos/UsuarioDAO.php';

/**
 * Capa de Negocio para Usuario - Validaciones y lógica
 *
 * Esta clase se encarga de aplicar validaciones y reglas de negocio
 * relacionadas con los usuarios antes de interactuar con la capa de datos.
 */
class UsuarioNegocio {
    private $usuarioDAO;

    /**
     * Constructor:
     * Inicializa el DAO encargado del acceso a datos de usuarios.
     */
    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }

    /**
     * Autentica un usuario mediante su email y contraseña.
     *
     * @param string $email       Correo del usuario
     * @param string $password    Contraseña ingresada
     *
     * @throws Exception Si los datos están vacíos o son inválidos
     * @return Usuario|false      Retorna el usuario válido o false si falla
     */
    public function autenticar($email, $password) {
        // Validación básica
        if (empty($email) || empty($password)) {
            throw new Exception("Email y contraseña son requeridos");
        }
        
        // Validación de formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido");
        }

        // Se delega al DAO para verificar credenciales
        $usuario = $this->usuarioDAO->autenticar($email, $password);

        // Si no existe el usuario, se lanza excepción
        if (!$usuario) {
            throw new Exception("Credenciales incorrectas");
        }

        return $usuario;
    }

    /**
     * Registra un nuevo usuario en el sistema.
     *
     * @param string $nombre   Nombre del usuario
     * @param string $email    Email único del usuario
     * @param string $password Contraseña del usuario
     * @param int    $rol_id   Rol asignado (por defecto: 2 = usuario común)
     *
     * @throws Exception Si hay campos vacíos o el email/password no son válidos
     * @return bool|int  ID generado o resultado de creación
     */
    public function registrar($nombre, $email, $password, $rol_id = 2) {
        // Validaciones obligatorias
        if (empty($nombre) || empty($email) || empty($password)) {
            throw new Exception("Todos los campos son requeridos");
        }

        // Email válido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido");
        }

        // Contraseña mínima requerida
        if (strlen($password) < 6) {
            throw new Exception("La contraseña debe tener al menos 6 caracteres");
        }

        // Se construye la entidad Usuario
        $usuario = new Usuario(
            null,       // ID autogenerado
            $nombre,
            $email,
            $password,
            $rol_id,
            true        // activo por defecto
        );

        return $this->usuarioDAO->registrar($usuario);
    }

    /**
     * Devuelve todos los usuarios registrados.
     */
    public function listar() {
        return $this->usuarioDAO->listar();
    }

    /**
     * Obtiene un usuario específico por ID.
     *
     * @param int $id  ID del usuario
     */
    public function obtenerPorId($id) {
        return $this->usuarioDAO->obtenerPorId($id);
    }
}
