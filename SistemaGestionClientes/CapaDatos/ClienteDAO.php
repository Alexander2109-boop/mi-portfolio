<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../CapaEntidades/Cliente.php';

/**
 * DAO para Cliente
 */
class ClienteDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    public function crear(Cliente $cliente) {
        $sql = "INSERT INTO Cliente (nombre, tipo_cliente_id, telefono, direccion, fecha_alta, activo) 
                VALUES (:nombre, :tipo_cliente_id, :telefono, :direccion, :fecha_alta, :activo)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':nombre' => $cliente->getNombre(),
            ':tipo_cliente_id' => $cliente->getTipoClienteId(),
            ':telefono' => $cliente->getTelefono(),
            ':direccion' => $cliente->getDireccion(),
            ':fecha_alta' => $cliente->getFechaAlta(),
            ':activo' => $cliente->getActivo()
        ]);
    }

    public function listar() {
        $sql = "SELECT c.*, tc.nombre as tipo_cliente_nombre 
                FROM Cliente c 
                INNER JOIN TipoCliente tc ON c.tipo_cliente_id = tc.id 
                ORDER BY c.nombre";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Cliente WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function actualizar(Cliente $cliente) {
        $sql = "UPDATE Cliente SET nombre = :nombre, tipo_cliente_id = :tipo_cliente_id, 
                telefono = :telefono, direccion = :direccion, fecha_alta = :fecha_alta, activo = :activo 
                WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':id' => $cliente->getId(),
            ':nombre' => $cliente->getNombre(),
            ':tipo_cliente_id' => $cliente->getTipoClienteId(),
            ':telefono' => $cliente->getTelefono(),
            ':direccion' => $cliente->getDireccion(),
            ':fecha_alta' => $cliente->getFechaAlta(),
            ':activo' => $cliente->getActivo()
        ]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM Cliente WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function obtenerTiposCliente() {
        $sql = "SELECT * FROM TipoCliente WHERE activo = 1 ORDER BY nombre";
        return $this->conexion->query($sql)->fetchAll();
    }
}
