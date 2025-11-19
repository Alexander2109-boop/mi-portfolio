<?php
require_once __DIR__ . '/../CapaDatos/ContactoDAO.php';

/**
 * Capa de Negocio para Contacto
 */
class ContactoNegocio {
    private $contactoDAO;

    public function __construct() {
        $this->contactoDAO = new ContactoDAO();
    }

    public function crear($cliente_id, $nombre, $cargo, $email, $telefono) {
        if (empty($nombre)) {
            throw new Exception("El nombre es requerido");
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido");
        }

        $contacto = new Contacto(null, $cliente_id, $nombre, $cargo, $email, $telefono);
        return $this->contactoDAO->crear($contacto);
    }

    public function listar() {
        return $this->contactoDAO->listar();
    }

    public function obtenerPorId($id) {
        return $this->contactoDAO->obtenerPorId($id);
    }

    public function actualizar($id, $cliente_id, $nombre, $cargo, $email, $telefono) {
        if (empty($nombre)) {
            throw new Exception("El nombre es requerido");
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido");
        }

        $contacto = new Contacto($id, $cliente_id, $nombre, $cargo, $email, $telefono);
        return $this->contactoDAO->actualizar($contacto);
    }

    public function eliminar($id) {
        return $this->contactoDAO->eliminar($id);
    }
}
