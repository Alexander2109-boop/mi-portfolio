<?php
require_once __DIR__ . '/../CapaDatos/ActividadDAO.php';

/**
 * Capa de Negocio para Actividad
 */
class ActividadNegocio {
    private $actividadDAO;

    public function __construct() {
        $this->actividadDAO = new ActividadDAO();
    }

    public function crear($oportunidad_id, $tipo_actividad_id, $fecha_hora, $descripcion) {
        if (empty($oportunidad_id) || empty($tipo_actividad_id)) {
            throw new Exception("Oportunidad y tipo son requeridos");
        }

        $actividad = new Actividad(null, $oportunidad_id, $tipo_actividad_id, $fecha_hora, $descripcion);
        return $this->actividadDAO->crear($actividad);
    }

    public function listar() {
        return $this->actividadDAO->listar();
    }

    public function obtenerPorId($id) {
        return $this->actividadDAO->obtenerPorId($id);
    }

    public function actualizar($id, $oportunidad_id, $tipo_actividad_id, $fecha_hora, $descripcion) {
        if (empty($oportunidad_id) || empty($tipo_actividad_id)) {
            throw new Exception("Oportunidad y tipo son requeridos");
        }

        $actividad = new Actividad($id, $oportunidad_id, $tipo_actividad_id, $fecha_hora, $descripcion);
        return $this->actividadDAO->actualizar($actividad);
    }

    public function eliminar($id) {
        return $this->actividadDAO->eliminar($id);
    }

    public function obtenerTipos() {
        return $this->actividadDAO->obtenerTipos();
    }
}
