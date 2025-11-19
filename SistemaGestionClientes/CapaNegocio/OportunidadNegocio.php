<?php
require_once __DIR__ . '/../CapaDatos/OportunidadDAO.php';

/**
 * Capa de Negocio para Oportunidad
 */
class OportunidadNegocio {
    private $oportunidadDAO;

    public function __construct() {
        $this->oportunidadDAO = new OportunidadDAO();
    }

    public function crear($cliente_id, $estado_oportunidad_id, $fecha_hora, $monto, $descripcion) {
        if (empty($cliente_id) || empty($estado_oportunidad_id)) {
            throw new Exception("Cliente y estado son requeridos");
        }

        $oportunidad = new Oportunidad(null, $cliente_id, $estado_oportunidad_id, $fecha_hora, $monto, $descripcion);
        return $this->oportunidadDAO->crear($oportunidad);
    }

    public function listar() {
        return $this->oportunidadDAO->listar();
    }

    public function obtenerPorId($id) {
        return $this->oportunidadDAO->obtenerPorId($id);
    }

    public function actualizar($id, $cliente_id, $estado_oportunidad_id, $fecha_hora, $monto, $descripcion) {
        if (empty($cliente_id) || empty($estado_oportunidad_id)) {
            throw new Exception("Cliente y estado son requeridos");
        }

        $oportunidad = new Oportunidad($id, $cliente_id, $estado_oportunidad_id, $fecha_hora, $monto, $descripcion);
        return $this->oportunidadDAO->actualizar($oportunidad);
    }

    public function eliminar($id) {
        return $this->oportunidadDAO->eliminar($id);
    }

    public function obtenerEstados() {
        return $this->oportunidadDAO->obtenerEstados();
    }
}
