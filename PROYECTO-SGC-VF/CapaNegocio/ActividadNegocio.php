<?php
require_once __DIR__ . '/../CapaDatos/ActividadDAO.php';
// Importante: No olvides importar la entidad si la vas a usar, 
// aunque en este diseño simplificado el DAO ya recibe los datos sueltos.

class ActividadNegocio {
    private $actividadDAO;

    public function __construct() {
        $this->actividadDAO = new ActividadDAO();
    }

    /**
     * Crear una nueva actividad
     */
    public function crear($oportunidad_id, $tipo_actividad_id, $descripcion) {
        // Validación de lógica de negocio
        if (empty($oportunidad_id) || empty($tipo_actividad_id) || empty($descripcion)) {
            throw new Exception("Todos los campos son obligatorios para crear la actividad.");
        }

        // CORRECCIÓN: El DAO ahora espera parámetros individuales, no un objeto.
        return $this->actividadDAO->crear($oportunidad_id, $tipo_actividad_id, $descripcion);
    }

    public function listar() {
        return $this->actividadDAO->listar();
    }

    public function obtenerPorId($id) {
        if (empty($id)) {
            throw new Exception("El ID de la actividad es necesario.");
        }
        return $this->actividadDAO->obtenerPorId($id);
    }

    /**
     * Actualizar una actividad existente
     */
    public function actualizar($id, $descripcion) {
        // Validación
        if (empty($id) || empty($descripcion)) {
            throw new Exception("El ID y la descripción son obligatorios para actualizar.");
        }

        // CORRECCIÓN: Llamamos al DAO con los parámetros que pide su nuevo método.
        return $this->actividadDAO->actualizar($id, $descripcion);
    }

    public function eliminar($id) {
        if (empty($id)) {
            throw new Exception("ID no válido para eliminar.");
        }
        return $this->actividadDAO->eliminar($id);
    }

    public function obtenerTipos() {
        return $this->actividadDAO->obtenerTipos();
    }
}