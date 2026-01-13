<?php
require_once __DIR__ . '/../CapaDatos/ProductoDAO.php';
require_once __DIR__ . '/../CapaEntidades/Producto.php'; // Importación necesaria

/**
 * Capa de Negocio para Producto
 */
class ProductoNegocio {
    private $productoDAO;

    public function __construct() {
        $this->productoDAO = new ProductoDAO();
    }

    /**
     * Crear un nuevo producto.
     */
    public function crear($nombre, $descripcion, $precio) {
        // Validación obligatoria del nombre
        if (empty($nombre)) {
            throw new Exception("El nombre es requerido");
        }

        // Validación de precio
        if ($precio < 0) {
            throw new Exception("El precio no puede ser negativo");
        }

        // Crear entidad Producto con estado activo por defecto (1)
        $producto = new Producto(
            null,        // ID nulo para nuevo registro
            $nombre,
            $descripcion,
            $precio,
            1            // activo por defecto
        );

        // Delegar al DAO (que usa sp_producto_upsert con ID 0)
        return $this->productoDAO->crear($producto);
    }

    /**
     * Listar todos los productos.
     */
    public function listar() {
        // Usa sp_producto_listar()
        return $this->productoDAO->listar();
    }

    /**
     * Obtener datos de un producto mediante su ID.
     */
    public function obtenerPorId($id) {
        if (empty($id)) {
            throw new Exception("ID de producto no proporcionado");
        }
        return $this->productoDAO->obtenerPorId($id);
    }

    /**
     * Actualizar un producto existente.
     */
    public function actualizar($id, $nombre, $descripcion, $precio, $activo) {
        // Validaciones obligatorias
        if (empty($id) || empty($nombre)) {
            throw new Exception("El ID y el nombre son requeridos");
        }

        if ($precio < 0) {
            throw new Exception("El precio no puede ser negativo");
        }

        // Crear objeto Producto con los datos actualizados
        $producto = new Producto(
            $id,
            $nombre,
            $descripcion,
            $precio,
            $activo
        );

        // Delegar al DAO (que usa sp_producto_upsert con el ID existente)
        return $this->productoDAO->actualizar($producto);
    }

    /**
     * Eliminar un producto por ID.
     */
    public function eliminar($id) {
        if (empty($id)) {
            throw new Exception("ID inválido para eliminar");
        }
        // Usa sp_producto_eliminar()
        return $this->productoDAO->eliminar($id);
    }
}