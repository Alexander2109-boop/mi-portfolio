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
        // Se obtiene la conexión PDO mediante la clase centralizada Conexion
        $this->conexion = Conexion::getConexion();
    }

    /**
     * Inserta un nuevo producto en la base de datos.
     */
    public function crear(Producto $producto) {
        $sql = "INSERT INTO Producto (nombre, descripcion, precio, activo) 
                VALUES (:nombre, :descripcion, :precio, :activo)";

        // Se prepara la consulta para prevenir inyección SQL
        $stmt = $this->conexion->prepare($sql);

        // Se ejecuta con los valores de la entidad Producto
        return $stmt->execute([
            ':nombre' => $producto->getNombre(),
            ':descripcion' => $producto->getDescripcion(),
            ':precio' => $producto->getPrecio(),
            ':activo' => $producto->getActivo()
        ]);
    }

    /**
     * Obtiene la lista de todos los productos.
     */
    public function listar() {
        $sql = "SELECT * FROM Producto ORDER BY nombre";

        // Consulta directa porque no requiere parámetros
        $stmt = $this->conexion->query($sql);

        return $stmt->fetchAll();
    }

    /**
     * Busca un producto por su ID.
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Producto WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);

        // Se ejecuta la búsqueda utilizando parámetros
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    /**
     * Actualiza la información de un producto existente.
     */
    public function actualizar(Producto $producto) {
        $sql = "UPDATE Producto 
                SET nombre = :nombre, descripcion = :descripcion, 
                    precio = :precio, activo = :activo 
                WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':id' => $producto->getId(),
            ':nombre' => $producto->getNombre(),
            ':descripcion' => $producto->getDescripcion(),
            ':precio' => $producto->getPrecio(),
            ':activo' => $producto->getActivo()
        ]);
    }

    /**
     * Elimina un producto por su ID.
     */
    public function eliminar($id) {
        $sql = "DELETE FROM Producto WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
