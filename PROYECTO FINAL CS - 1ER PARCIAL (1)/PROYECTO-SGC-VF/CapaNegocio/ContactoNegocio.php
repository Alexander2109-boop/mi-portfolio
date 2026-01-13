<?php
require_once __DIR__ . '/../CapaDatos/ContactoDAO.php';

/**
 * Capa de Negocio para Contacto
 * 
 * Esta clase actúa como intermediaria entre la capa de datos (DAO)
 * y las capas superiores como controladores o servicios.
 * Aquí se realizan validaciones y reglas de negocio antes de ejecutar
 * operaciones en la base de datos.
 */
class ContactoNegocio {
    private $contactoDAO;

    /**
     * Al crear la capa de negocio se instancia el DAO correspondiente.
     */
    public function __construct() {
        $this->contactoDAO = new ContactoDAO();
    }

    /**
     * Crear un nuevo contacto
     * 
     * @param int $cliente_id  ID del cliente asociado
     * @param string $nombre   Nombre del contacto (requerido)
     * @param string $cargo    Cargo dentro de la empresa
     * @param string $email    Email (validado si no está vacío)
     * @param string $telefono Teléfono del contacto
     */
    public function crear($cliente_id, $nombre, $cargo, $email, $telefono) {
        // Validación mínima obligatoria del nombre
        if (empty($nombre)) {
            throw new Exception("El nombre es requerido");
        }

        // Validación de formato del email solo si existe
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido");
        }

        // Crear entidad Contacto
        $contacto = new Contacto(null, $cliente_id, $nombre, $cargo, $email, $telefono);

        // Delegar la creación al DAO
        return $this->contactoDAO->crear($contacto);
    }

    /**
     * Listar todos los contactos disponibles
     */
    public function listar() {
        return $this->contactoDAO->listar();
    }

    /**
     * Obtener un contacto específico por ID
     */
    public function obtenerPorId($id) {
        return $this->contactoDAO->obtenerPorId($id);
    }

    /**
     * Actualizar un contacto existente
     * 
     * Se aplican nuevamente las validaciones de negocio.
     */
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

    /**
     * Eliminar un contacto por ID
     */
    public function eliminar($id) {
        return $this->contactoDAO->eliminar($id);
    }
}
