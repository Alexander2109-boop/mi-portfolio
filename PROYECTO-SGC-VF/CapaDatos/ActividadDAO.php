<?php
require_once __DIR__ . '/Conexion.php'; // Importa la clase de conexión a la base de datos
require_once __DIR__ . '/../CapaEntidades/Actividad.php'; // Importa la entidad Actividad

/**
 * DAO para Actividad
 * Encargado de realizar operaciones CRUD sobre la tabla Actividad
 */
class ActividadDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    /**
     * Registra una actividad usando sp_actividad_registrar
     */
    public function crear($oportunidad_id, $tipo_id, $descripcion) {
        $sql = "CALL sp_actividad_registrar(?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$oportunidad_id, $tipo_id, $descripcion]);
    }

    /**
     * Lista actividades usando sp_actividad_listar (que llama a la vista)
     */
    public function listar() {
        $sql = "CALL sp_actividad_listar()";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una actividad por ID usando sp_actividad_obtener
     */
    public function obtenerPorId($id) {
        $sql = "CALL sp_actividad_obtener(?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza la descripción usando sp_actividad_actualizar
     */
    public function actualizar($id, $descripcion) {
        $sql = "CALL sp_actividad_actualizar(?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id, $descripcion]);
    }

    /**
     * Elimina una actividad usando sp_actividad_eliminar
     */
    public function eliminar($id) {
        $sql = "CALL sp_actividad_eliminar(?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Obtiene los tipos de actividad usando sp_actividad_tipos
     */
    public function obtenerTipos() {
        $sql = "CALL sp_actividad_tipos()";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}