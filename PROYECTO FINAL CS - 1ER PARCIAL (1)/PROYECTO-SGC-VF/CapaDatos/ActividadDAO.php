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
        // Obtiene la conexión a la base de datos mediante el patrón Singleton
        $this->conexion = Conexion::getConexion();
    }

    /**
     * Crea una nueva actividad en la base de datos
     */
    public function crear(Actividad $actividad) {
        $sql = "INSERT INTO Actividad (oportunidad_id, tipo_actividad_id, fecha_hora, descripcion) 
                VALUES (:oportunidad_id, :tipo_actividad_id, :fecha_hora, :descripcion)";
        $stmt = $this->conexion->prepare($sql); // Prepara la consulta
        return $stmt->execute([
            ':oportunidad_id' => $actividad->getOportunidadId(),
            ':tipo_actividad_id' => $actividad->getTipoActividadId(),
            ':fecha_hora' => $actividad->getFechaHora(),
            ':descripcion' => $actividad->getDescripcion()
        ]); // Ejecuta con los valores obtenidos desde el objeto Actividad
    }

    /**
     * Lista todas las actividades, uniendo con la tabla TipoActividad
     */
    public function listar() {
        $sql = "SELECT a.*, ta.nombre as tipo_nombre 
                FROM Actividad a 
                INNER JOIN TipoActividad ta ON a.tipo_actividad_id = ta.id 
                ORDER BY a.fecha_hora DESC";
        $stmt = $this->conexion->query($sql); // Ejecuta la consulta directamente
        return $stmt->fetchAll(); // Retorna todas las filas
    }

    /**
     * Obtiene una actividad según su ID
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Actividad WHERE id = :id";
        $stmt = $this->conexion->prepare($sql); // Prepara la consulta
        $stmt->execute([':id' => $id]); // Ejecuta con el parámetro
        return $stmt->fetch(); // Retorna una sola fila
    }

    /**
     * Actualiza una actividad existente
     */
    public function actualizar(Actividad $actividad) {
        $sql = "UPDATE Actividad SET oportunidad_id = :oportunidad_id, tipo_actividad_id = :tipo_actividad_id, 
                fecha_hora = :fecha_hora, descripcion = :descripcion WHERE id = :id";
        $stmt = $this->conexion->prepare($sql); // Prepara la consulta
        return $stmt->execute([
            ':id' => $actividad->getId(),
            ':oportunidad_id' => $actividad->getOportunidadId(),
            ':tipo_actividad_id' => $actividad->getTipoActividadId(),
            ':fecha_hora' => $actividad->getFechaHora(),
            ':descripcion' => $actividad->getDescripcion()
        ]); // Ejecuta con los valores actualizados
    }

    /**
     * Elimina una actividad según su ID
     */
    public function eliminar($id) {
        $sql = "DELETE FROM Actividad WHERE id = :id";
        $stmt = $this->conexion->prepare($sql); // Prepara la consulta
        return $stmt->execute([':id' => $id]); // Ejecuta con el parámetro
    }

    /**
     * Obtiene los tipos de actividad activos
     */
    public function obtenerTipos() {
        $sql = "SELECT * FROM TipoActividad WHERE activo = 1 ORDER BY nombre";
        return $this->conexion->query($sql)->fetchAll(); // Retorna todos los tipos activos
    }
}
