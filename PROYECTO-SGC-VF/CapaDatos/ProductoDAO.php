<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../CapaEntidades/Producto.php';

/**
 * DAO para Producto
 *
 * Esta clase gestiona todas las operaciones CRUD relacionadas
 * con la tabla Producto en la base de datos.
 */
class ProductoDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    /**
     * Inserta un nuevo producto usando sp_producto_upsert
     */
    public function crear(Producto $producto) {
        $sql = "CALL sp_producto_upsert(?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        // Pasamos 0 en el ID para que el procedimiento realice un INSERT
        return $stmt->execute([
            0,
            $producto->getNombre(),
            $producto->getDescripcion(),
            $producto->getPrecio(),
            $producto->getActivo()
        ]);
    }

    /**
     * Lista todos los productos usando sp_producto_listar
     */
    public function listar() {
        $sql = "CALL sp_producto_listar()";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un producto por su ID usando sp_producto_obtener_uno
     */
    public function obtenerPorId($id) {
        $sql = "CALL sp_producto_obtener_uno(?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza la información usando sp_producto_upsert
     */
    public function actualizar(Producto $producto) {
        $sql = "CALL sp_producto_upsert(?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        // Pasamos el ID existente para que el procedimiento realice un UPDATE
        return $stmt->execute([
            $producto->getId(),
            $producto->getNombre(),
            $producto->getDescripcion(),
            $producto->getPrecio(),
            $producto->getActivo()
        ]);
    }

    /**
     * Elimina un producto usando sp_producto_eliminar
     */
    public function eliminar($id) {
        $sql = "CALL sp_producto_eliminar(?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }
}