<?php
require_once __DIR__ . '/../CapaDatos/ProductoDAO.php';

/**
 * Capa de Negocio para Producto
 *
 * Encargada de validar la información y aplicar reglas de negocio
 * antes de delegar las operaciones CRUD al ProductoDAO.
 */
class ProductoNegocio {
    private $productoDAO;

    /**
     * Constructor:
     * Inicializa el DAO que se encargará de la persistencia.
     */
    public function __construct() {
        $this->productoDAO = new ProductoDAO();
    }

    /**
     * Crear un nuevo producto.
     *
     * @param string $nombre        Nombre del producto
     * @param string $descripcion   Descripción del producto
     * @param float  $precio        Precio del producto (no debe ser negativo)
     *
     * @throws Exception Si el nombre está vacío o el precio es inválido
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

        // Crear entidad Producto con estado activo por defecto
        $producto = new Producto(
            null,        // ID autogenerado
            $nombre,
            $descripcion,
            $precio,
            true         // activo por defecto
        );

        return $this->productoDAO->crear($producto);
    }

    /**
     * Listar todos los productos.
     *
     * @return array Lista de productos (activos/inactivos según almacenamiento)
     */
    public function listar() {
        return $this->productoDAO->listar();
    }

    /**
     * Obtener datos de un producto mediante su ID.
     *
     * @param int $id ID del producto
     */
    public function obtenerPorId($id) {
        return $this->productoDAO->obtenerPorId($id);
    }

    /**
     * Actualizar un producto existente.
     *
     * @param int     $id           ID del producto
     * @param string  $nombre       Nombre actualizado
     * @param string  $descripcion  Descripción actualizada
     * @param float   $precio       Precio actualizado
     * @param bool    $activo       Estado del producto
     *
     * @throws Exception Si el nombre está vacío o el precio es negativo
     */
    public function actualizar($id, $nombre, $descripcion, $precio, $activo) {
        // Validación obligatoria
        if (empty($nombre)) {
            throw new Exception("El nombre es requerido");
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

        return $this->productoDAO->actualizar($producto);
    }

    /**
     * Eliminar un producto por ID.
     *
     * @param int $id ID del producto
     */
    public function eliminar($id) {
        return $this->productoDAO->eliminar($id);
    }
}
