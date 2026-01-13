<?php
require_once __DIR__ . '/../CapaDatos/ContactoDAO.php';
require_once __DIR__ . '/../CapaEntidades/Contacto.php';

/**
 * Capa de Negocio para Contacto
 */
class ContactoNegocio {
    private $contactoDAO;

    public function __construct() {
        $this->contactoDAO = new ContactoDAO();
    }

    /**
     * Crear un nuevo contacto
     */
    public function crear($cliente_id, $nombre, $cargo, $email, $telefono) {
        // Validación de nombre
        if (empty($nombre)) {
            throw new Exception("El nombre es requerido");
        }

        // Validación de email
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Formato de email inválido");
        }

        // Creamos la entidad. ID en null porque es nuevo.
        $contacto = new Contacto(null, $cliente_id, $nombre, $cargo, $email, $telefono);

        // CORRECCIÓN: Usamos el método guardar() del DAO que llama a sp_contacto_upsert
        return $this->contactoDAO->guardar($contacto);
    }

    /**
     * Listar todos los contactos
     */
    public function listar() {
        $resultado = $this->contactoDAO->listar(); // Llama al método listar() del DAO
        return $resultado; // Asegúrate de que retorna algo
    }

    /**
     * Obtener un contacto por ID
     */
    public function obtenerPorId($id) {
        if (empty($id)) {
            throw new Exception("ID de contacto no válido");
        }
        return $this->contactoDAO->obtenerPorId($id);
    }

    /**
     * Actualizar un contacto existente
     */
    public function actualizar($id, $cliente_id, $nombre, $cargo, $email, $telefono) {
        if (empty($id) || empty($nombre)) {
            throw new Exception("ID y Nombre son obligatorios");
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Formato de email inválido");
        }

        // Creamos la entidad con el ID para actualizar
        $contacto = new Contacto($id, $cliente_id, $nombre, $cargo, $email, $telefono);

        // CORRECCIÓN: Usamos el método guardar()
        return $this->contactoDAO->guardar($contacto);
    }

    /**
     * Eliminar un contacto
     */
    public function eliminar($id) {
        if (empty($id)) {
            throw new Exception("ID requerido para eliminar");
        }
        return $this->contactoDAO->eliminar($id);
    }
}