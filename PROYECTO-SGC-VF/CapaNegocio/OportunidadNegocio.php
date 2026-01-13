<?php
require_once __DIR__ . '/../CapaDatos/OportunidadDAO.php';
require_once __DIR__ . '/../CapaEntidades/Oportunidad.php'; // Importación añadida

/**
 * Capa de Negocio para Oportunidad
 */
class OportunidadNegocio {
    private $oportunidadDAO;

    public function __construct() {
        $this->oportunidadDAO = new OportunidadDAO();
    }

    /**
     * Crear una nueva oportunidad comercial
     */
    public function crear($cliente_id, $estado_oportunidad_id, $fecha_hora, $monto, $descripcion) {
        // Validación de negocio
        if (empty($cliente_id) || empty($estado_oportunidad_id)) {
            throw new Exception("El cliente y el estado son obligatorios para la oportunidad.");
        }

        // Crear la entidad Oportunidad
        $oportunidad = new Oportunidad(
            null, 
            $cliente_id,
            $estado_oportunidad_id,
            $fecha_hora,
            $monto,
            $descripcion
        );

        // Delegar al DAO (que usa sp_oportunidad_registrar_completo)
        return $this->oportunidadDAO->crear($oportunidad);
    }

    /**
     * Listar todas las oportunidades (usa la vista detallada)
     */
    public function listar() {
        return $this->oportunidadDAO->listar();
    }

    /**
     * Obtener una oportunidad por su ID
     */
    public function obtenerPorId($id) {
        if (empty($id)) {
            throw new Exception("ID de oportunidad no válido.");
        }
        return $this->oportunidadDAO->obtenerPorId($id);
    }

    /**
     * Actualizar una oportunidad existente
     */
    public function actualizar($id, $cliente_id, $estado_oportunidad_id, $fecha_hora, $monto, $descripcion) {
        if (empty($id) || empty($cliente_id) || empty($estado_oportunidad_id)) {
            throw new Exception("El ID, cliente y estado son obligatorios para actualizar.");
        }

        $oportunidad = new Oportunidad(
            $id,
            $cliente_id,
            $estado_oportunidad_id,
            $fecha_hora,
            $monto,
            $descripcion
        );

        // Delegar al DAO (que usa sp_oportunidad_actualizar_completo)
        return $this->oportunidadDAO->actualizar($oportunidad);
    }

    /**
     * Eliminar una oportunidad por ID
     */
    public function eliminar($id) {
        if (empty($id)) {
            throw new Exception("ID requerido para eliminar la oportunidad.");
        }
        return $this->oportunidadDAO->eliminar($id);
    }

    /**
     * Obtener los estados disponibles
     */
    public function obtenerEstados() {
        return $this->oportunidadDAO->obtenerEstados();
    }
}