<?php
require_once __DIR__ . '/../CapaDatos/ClienteDAO.php';

/**
 * Capa de Negocio para Cliente
 * Se encarga de aplicar reglas de negocio antes de interactuar con la capa de datos.
 */
class ClienteNegocio {
    // DAO encargado de gestionar las operaciones de base de datos relacionadas con clientes
    private $clienteDAO;

    /**
     * Constructor
     * Inicializa la instancia del ClienteDAO
     */
    public function __construct() {
        $this->clienteDAO = new ClienteDAO();
    }

    /**
     * Crear un nuevo cliente
     * Aplica validaciones y luego delega la creación al DAO.
     */
    public function crear($nombre, $tipo_cliente_id, $telefono, $direccion, $fecha_alta) {
        // Validación de campos obligatorios
        if (empty($nombre)) {
            throw new Exception("El nombre es requerido");
        }

        // Crear entidad Cliente con los datos proporcionados
        $cliente = new Cliente(null, $nombre, $tipo_cliente_id, $telefono, $direccion, $fecha_alta, true);

        // Delegar al DAO la inserción en la base de datos
        return $this->clienteDAO->crear($cliente);
    }

    /**
     * Listar todos los clientes
     */
    public function listar() {
        return $this->clienteDAO->listar();
    }

    /**
     * Obtener un cliente por su ID
     */
    public function obtenerPorId($id) {
        return $this->clienteDAO->obtenerPorId($id);
    }

    /**
     * Actualizar un cliente existente
     * Aplica validaciones básicas antes de pasar la entidad al DAO.
     */
    public function actualizar($id, $nombre, $tipo_cliente_id, $telefono, $direccion, $fecha_alta, $activo) {
        // Validar campo obligatorio
        if (empty($nombre)) {
            throw new Exception("El nombre es requerido");
        }

        // Crear entidad Cliente con valores actualizados
        $cliente = new Cliente($id, $nombre, $tipo_cliente_id, $telefono, $direccion, $fecha_alta, $activo);

        // Delegar la actualización al DAO
        return $this->clienteDAO->actualizar($cliente);
    }

    /**
     * Eliminar un cliente
     */
    public function eliminar($id) {
        return $this->clienteDAO->eliminar($id);
    }

    /**
     * Obtener los tipos de cliente disponibles (ej.: Empresa, Persona, Institución)
     */
    public function obtenerTiposCliente() {
        return $this->clienteDAO->obtenerTiposCliente();
    }
}
