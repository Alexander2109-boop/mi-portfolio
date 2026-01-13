<?php
require_once __DIR__ . '/../CapaDatos/ActividadDAO.php';

/**
 * Capa de Negocio para Actividad
 * Esta clase actúa como intermediaria entre los controladores y la capa de datos (DAO).
 * Aquí se valida la lógica de negocio antes de realizar operaciones con la base de datos.
 */
class ActividadNegocio {
    // Instancia del DAO encargado del acceso a datos para actividades
    private $actividadDAO;

    /**
     * Constructor
     * Inicializa el DAO para que esté disponible en los métodos del negocio.
     */
    public function __construct() {
        $this->actividadDAO = new ActividadDAO();
    }

    /**
     * Crear una nueva actividad
     * Valida datos obligatorios antes de delegar la creación al DAO.
     */
    public function crear($oportunidad_id, $tipo_actividad_id, $fecha_hora, $descripcion) {
        // Validación básica de campos obligatorios
        if (empty($oportunidad_id) || empty($tipo_actividad_id)) {
            throw new Exception("Oportunidad y tipo son requeridos");
        }

        // Crear entidad Actividad para enviarla al DAO
        $actividad = new Actividad(null, $oportunidad_id, $tipo_actividad_id, $fecha_hora, $descripcion);

        // Delegar al DAO la inserción en la base de datos
        return $this->actividadDAO->crear($actividad);
    }

    /**
     * Listar todas las actividades
     */
    public function listar() {
        return $this->actividadDAO->listar();
    }

    /**
     * Obtener una actividad por su ID
     */
    public function obtenerPorId($id) {
        return $this->actividadDAO->obtenerPorId($id);
    }

    /**
     * Actualizar una actividad existente
     * Aplica validaciones y delega al DAO la actualización
     */
    public function actualizar($id, $oportunidad_id, $tipo_actividad_id, $fecha_hora, $descripcion) {
        // Validación de campos requeridos
        if (empty($oportunidad_id) || empty($tipo_actividad_id)) {
            throw new Exception("Oportunidad y tipo son requeridos");
        }

        // Construir la entidad con los datos actualizados
        $actividad = new Actividad($id, $oportunidad_id, $tipo_actividad_id, $fecha_hora, $descripcion);

        // Delegar la actualización al DAO
        return $this->actividadDAO->actualizar($actividad);
    }

    /**
     * Eliminar una actividad
     */
    public function eliminar($id) {
        return $this->actividadDAO->eliminar($id);
    }

    /**
     * Obtener los tipos de actividad disponibles (llamadas, visitas, reuniones, etc.)
     */
    public function obtenerTipos() {
        return $this->actividadDAO->obtenerTipos();
    }
}
