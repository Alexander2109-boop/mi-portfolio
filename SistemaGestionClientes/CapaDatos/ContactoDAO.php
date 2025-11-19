<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../CapaEntidades/Contacto.php';

/**
 * DAO para Contacto
 */
class ContactoDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    public function crear(Contacto $contacto) {
        $sql = "INSERT INTO Contacto (cliente_id, nombre, cargo, email, telefono) 
                VALUES (:cliente_id, :nombre, :cargo, :email, :telefono)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':cliente_id' => $contacto->getClienteId(),
            ':nombre' => $contacto->getNombre(),
            ':cargo' => $contacto->getCargo(),
            ':email' => $contacto->getEmail(),
            ':telefono' => $contacto->getTelefono()
        ]);
    }

    public function listar() {
        $sql = "SELECT con.*, c.nombre as cliente_nombre 
                FROM Contacto con 
                INNER JOIN Cliente c ON con.cliente_id = c.id 
                ORDER BY con.nombre";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Contacto WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function actualizar(Contacto $contacto) {
        $sql = "UPDATE Contacto SET cliente_id = :cliente_id, nombre = :nombre, 
                cargo = :cargo, email = :email, telefono = :telefono WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':id' => $contacto->getId(),
            ':cliente_id' => $contacto->getClienteId(),
            ':nombre' => $contacto->getNombre(),
            ':cargo' => $contacto->getCargo(),
            ':email' => $contacto->getEmail(),
            ':telefono' => $contacto->getTelefono()
        ]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM Contacto WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
