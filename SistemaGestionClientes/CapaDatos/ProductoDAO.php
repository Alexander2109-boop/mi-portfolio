<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../CapaEntidades/Producto.php';

/**
 * DAO para Producto
 */
class ProductoDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    public function crear(Producto $producto) {
        $sql = "INSERT INTO Producto (nombre, descripcion, precio, activo) 
                VALUES (:nombre, :descripcion, :precio, :activo)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':nombre' => $producto->getNombre(),
            ':descripcion' => $producto->getDescripcion(),
            ':precio' => $producto->getPrecio(),
            ':activo' => $producto->getActivo()
        ]);
    }

    public function listar() {
        $sql = "SELECT * FROM Producto ORDER BY nombre";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Producto WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function actualizar(Producto $producto) {
        $sql = "UPDATE Producto SET nombre = :nombre, descripcion = :descripcion, 
                precio = :precio, activo = :activo WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':id' => $producto->getId(),
            ':nombre' => $producto->getNombre(),
            ':descripcion' => $producto->getDescripcion(),
            ':precio' => $producto->getPrecio(),
            ':activo' => $producto->getActivo()
        ]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM Producto WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
