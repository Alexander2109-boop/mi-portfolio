<?php
require_once __DIR__ . '/../CapaDatos/ClienteDAO.php';
require_once __DIR__ . '/../CapaEntidades/Cliente.php';

/**
 * Capa de Negocio para Cliente
 */
class ClienteNegocio {
    private $clienteDAO;

    public function __construct() {
        $this->clienteDAO = new ClienteDAO();
    }

    /**
     * Crear un nuevo cliente
     */
    public function crear($nombre, $tipo_cliente_id, $telefono, $direccion, $fecha_alta) {
        // Validación de lógica de negocio
        if (empty($nombre)) {
            throw new Exception("El nombre es requerido");
        }

        // Creamos el objeto cliente. El ID es null porque es nuevo.
        $cliente = new Cliente(null, $nombre, $tipo_cliente_id, $telefono, $direccion, $fecha_alta, true);

        // CORRECCIÓN: Llamamos al método guardar() del DAO
        return $this->clienteDAO->guardar($cliente);
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
        if (empty($id)) {
            throw new Exception("ID no válido");
        }
        return $this->clienteDAO->obtenerPorId($id);
    }

    /**
     * Actualizar un cliente existente
     */
    public function actualizar($id, $nombre, $tipo_cliente_id, $telefono, $direccion, $fecha_alta, $activo) {
        if (empty($nombre) || empty($id)) {
            throw new Exception("El nombre y el ID son requeridos");
        }

        // Creamos el objeto con el ID existente
        $cliente = new Cliente($id, $nombre, $tipo_cliente_id, $telefono, $direccion, $fecha_alta, $activo);

        // CORRECCIÓN: También usamos guardar(), ya que el procedimiento en la BD detecta que tiene ID y actualiza.
        return $this->clienteDAO->guardar($cliente);
    }

    /**
     * Eliminar un cliente
     */
    public function eliminar($id) {
        if (empty($id)) {
            throw new Exception("ID requerido para eliminar");
        }
        return $this->clienteDAO->eliminar($id);
    }

    /**
     * Obtener los tipos de cliente
     */
    public function obtenerTiposCliente() {
        return $this->clienteDAO->obtenerTiposCliente();
    }
}