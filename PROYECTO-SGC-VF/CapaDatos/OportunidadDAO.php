<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../CapaEntidades/Oportunidad.php';

/**
 * DAO para Oportunidad
 * 
 * Esta clase encapsula todas las operaciones de acceso a datos relacionadas
 * con la tabla Oportunidad en la base de datos.
 */
class OportunidadDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    /**
     * Crea una nueva oportunidad usando sp_oportunidad_registrar_completo
     */
    public function crear(Oportunidad $oportunidad) {
        $sql = "CALL sp_oportunidad_registrar_completo(?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            $oportunidad->getClienteId(),
            $oportunidad->getEstadoOportunidadId(),
            $oportunidad->getMonto(),
            $oportunidad->getDescripcion(),
            $oportunidad->getFechaHora()
        ]);
    }

    /**
     * Lista todas las oportunidades usando la vista detallada
     */
    public function listar() {
        $sql = "SELECT * FROM vista_oportunidades_detalladas ORDER BY fecha_hora DESC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una oportunidad por su ID
     */
    public function obtenerPorId($id) {
        $sql = "CALL sp_oportunidad_obtener_uno(?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza una oportunidad usando sp_oportunidad_actualizar_completo
     */
    public function actualizar(Oportunidad $oportunidad) {
        $sql = "CALL sp_oportunidad_actualizar_completo(?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            $oportunidad->getId(),
            $oportunidad->getClienteId(),
            $oportunidad->getEstadoOportunidadId(),
            $oportunidad->getMonto(),
            $oportunidad->getDescripcion(),
            $oportunidad->getFechaHora()
        ]);
    }

    /**
     * Elimina una oportunidad usando sp_oportunidad_eliminar
     */
    public function eliminar($id) {
        $sql = "CALL sp_oportunidad_eliminar(?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Obtiene los estados posibles usando sp_oportunidad_estados
     */
    public function obtenerEstados() {
        $sql = "CALL sp_oportunidad_estados()";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}