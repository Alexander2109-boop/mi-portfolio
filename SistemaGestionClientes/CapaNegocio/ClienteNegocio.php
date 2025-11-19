<?php
require_once __DIR__ . '/../CapaDatos/ClienteDAO.php';

/**
 * Capa de Negocio para Cliente
 */
class ClienteNegocio {
    private $clienteDAO;

    public function __construct() {
        $this->clienteDAO = new ClienteDAO();
    }

    public function crear($nombre, $tipo_cliente_id, $telefono, $direccion, $fecha_alta) {
        if (empty($nombre)) {
            throw new Exception("El nombre es requerido");
        }

        $cliente = new Cliente(null, $nombre, $tipo_cliente_id, $telefono, $direccion, $fecha_alta, true);
        return $this->clienteDAO->crear($cliente);
    }

    public function listar() {
        return $this->clienteDAO->listar();
    }

    public function obtenerPorId($id) {
        return $this->clienteDAO->obtenerPorId($id);
    }

    public function actualizar($id, $nombre, $tipo_cliente_id, $telefono, $direccion, $fecha_alta, $activo) {
        if (empty($nombre)) {
            throw new Exception("El nombre es requerido");
        }

        $cliente = new Cliente($id, $nombre, $tipo_cliente_id, $telefono, $direccion, $fecha_alta, $activo);
        return $this->clienteDAO->actualizar($cliente);
    }

    public function eliminar($id) {
        return $this->clienteDAO->eliminar($id);
    }

    public function obtenerTiposCliente() {
        return $this->clienteDAO->obtenerTiposCliente();
    }
}
