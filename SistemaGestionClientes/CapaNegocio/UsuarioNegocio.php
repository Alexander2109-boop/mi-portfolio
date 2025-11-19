<?php
require_once __DIR__ . '/../CapaDatos/UsuarioDAO.php';

/**
 * Capa de Negocio para Usuario - Validaciones y lógica
 */
class UsuarioNegocio {
    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function autenticar($email, $password) {
        if (empty($email) || empty($password)) {
            throw new Exception("Email y contraseña son requeridos");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido");
        }

        $usuario = $this->usuarioDAO->autenticar($email, $password);
        if (!$usuario) {
            throw new Exception("Credenciales incorrectas");
        }

        return $usuario;
    }

    public function registrar($nombre, $email, $password, $rol_id = 2) {
        if (empty($nombre) || empty($email) || empty($password)) {
            throw new Exception("Todos los campos son requeridos");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido");
        }

        if (strlen($password) < 6) {
            throw new Exception("La contraseña debe tener al menos 6 caracteres");
        }

        $usuario = new Usuario(null, $nombre, $email, $password, $rol_id, true);
        return $this->usuarioDAO->registrar($usuario);
    }

    public function listar() {
        return $this->usuarioDAO->listar();
    }

    public function obtenerPorId($id) {
        return $this->usuarioDAO->obtenerPorId($id);
    }
}
