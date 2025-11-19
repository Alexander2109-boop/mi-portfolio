<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../CapaEntidades/Actividad.php';

/**
 * DAO para Actividad
 */
class ActividadDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    public function crear(Actividad $actividad) {
        $sql = "INSERT INTO Actividad (oportunidad_id, tipo_actividad_id, fecha_hora, descripcion) 
                VALUES (:oportunidad_id, :tipo_actividad_id, :fecha_hora, :descripcion)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':oportunidad_id' => $actividad->getOportunidadId(),
            ':tipo_actividad_id' => $actividad->getTipoActividadId(),
            ':fecha_hora' => $actividad->getFechaHora(),
            ':descripcion' => $actividad->getDescripcion()
        ]);
    }

    public function listar() {
        $sql = "SELECT a.*, ta.nombre as tipo_nombre 
                FROM Actividad a 
                INNER JOIN TipoActividad ta ON a.tipo_actividad_id = ta.id 
                ORDER BY a.fecha_hora DESC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Actividad WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function actualizar(Actividad $actividad) {
        $sql = "UPDATE Actividad SET oportunidad_id = :oportunidad_id, tipo_actividad_id = :tipo_actividad_id, 
                fecha_hora = :fecha_hora, descripcion = :descripcion WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':id' => $actividad->getId(),
            ':oportunidad_id' => $actividad->getOportunidadId(),
            ':tipo_actividad_id' => $actividad->getTipoActividadId(),
            ':fecha_hora' => $actividad->getFechaHora(),
            ':descripcion' => $actividad->getDescripcion()
        ]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM Actividad WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function obtenerTipos() {
        $sql = "SELECT * FROM TipoActividad WHERE activo = 1 ORDER BY nombre";
        return $this->conexion->query($sql)->fetchAll();
    }
}
