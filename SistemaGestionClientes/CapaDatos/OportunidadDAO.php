<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../CapaEntidades/Oportunidad.php';

/**
 * DAO para Oportunidad
 */
class OportunidadDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    public function crear(Oportunidad $oportunidad) {
        $sql = "INSERT INTO Oportunidad (cliente_id, estado_oportunidad_id, fecha_hora, monto, descripcion) 
                VALUES (:cliente_id, :estado_oportunidad_id, :fecha_hora, :monto, :descripcion)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':cliente_id' => $oportunidad->getClienteId(),
            ':estado_oportunidad_id' => $oportunidad->getEstadoOportunidadId(),
            ':fecha_hora' => $oportunidad->getFechaHora(),
            ':monto' => $oportunidad->getMonto(),
            ':descripcion' => $oportunidad->getDescripcion()
        ]);
    }

    public function listar() {
        $sql = "SELECT o.*, c.nombre as cliente_nombre, eo.nombre as estado_nombre 
                FROM Oportunidad o 
                INNER JOIN Cliente c ON o.cliente_id = c.id 
                INNER JOIN EstadoOportunidad eo ON o.estado_oportunidad_id = eo.id 
                ORDER BY o.fecha_hora DESC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Oportunidad WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function actualizar(Oportunidad $oportunidad) {
        $sql = "UPDATE Oportunidad SET cliente_id = :cliente_id, estado_oportunidad_id = :estado_oportunidad_id, 
                fecha_hora = :fecha_hora, monto = :monto, descripcion = :descripcion WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':id' => $oportunidad->getId(),
            ':cliente_id' => $oportunidad->getClienteId(),
            ':estado_oportunidad_id' => $oportunidad->getEstadoOportunidadId(),
            ':fecha_hora' => $oportunidad->getFechaHora(),
            ':monto' => $oportunidad->getMonto(),
            ':descripcion' => $oportunidad->getDescripcion()
        ]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM Oportunidad WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function obtenerEstados() {
        $sql = "SELECT * FROM EstadoOportunidad ORDER BY nombre";
        return $this->conexion->query($sql)->fetchAll();
    }
}
