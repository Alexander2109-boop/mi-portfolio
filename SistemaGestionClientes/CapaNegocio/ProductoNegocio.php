<?php
require_once __DIR__ . '/../CapaDatos/ProductoDAO.php';

/**
 * Capa de Negocio para Producto
 */
class ProductoNegocio {
    private $productoDAO;

    public function __construct() {
        $this->productoDAO = new ProductoDAO();
    }

    public function crear($nombre, $descripcion, $precio) {
        if (empty($nombre)) {
            throw new Exception("El nombre es requerido");
        }

        if ($precio < 0) {
            throw new Exception("El precio no puede ser negativo");
        }

        $producto = new Producto(null, $nombre, $descripcion, $precio, true);
        return $this->productoDAO->crear($producto);
    }

    public function listar() {
        return $this->productoDAO->listar();
    }

    public function obtenerPorId($id) {
        return $this->productoDAO->obtenerPorId($id);
    }

    public function actualizar($id, $nombre, $descripcion, $precio, $activo) {
        if (empty($nombre)) {
            throw new Exception("El nombre es requerido");
        }

        if ($precio < 0) {
            throw new Exception("El precio no puede ser negativo");
        }

        $producto = new Producto($id, $nombre, $descripcion, $precio, $activo);
        return $this->productoDAO->actualizar($producto);
    }

    public function eliminar($id) {
        return $this->productoDAO->eliminar($id);
    }
}
