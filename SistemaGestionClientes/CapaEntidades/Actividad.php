<?php
/**
 * Entidad Actividad
 */
class Actividad {
    private $id;
    private $oportunidad_id;
    private $tipo_actividad_id;
    private $fecha_hora;
    private $descripcion;

    public function __construct($id = null, $oportunidad_id = null, $tipo_actividad_id = null, $fecha_hora = null, $descripcion = '') {
        $this->id = $id;
        $this->oportunidad_id = $oportunidad_id;
        $this->tipo_actividad_id = $tipo_actividad_id;
        $this->fecha_hora = $fecha_hora;
        $this->descripcion = $descripcion;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getOportunidadId() { return $this->oportunidad_id; }
    public function setOportunidadId($oportunidad_id) { $this->oportunidad_id = $oportunidad_id; }
    
    public function getTipoActividadId() { return $this->tipo_actividad_id; }
    public function setTipoActividadId($tipo_actividad_id) { $this->tipo_actividad_id = $tipo_actividad_id; }
    
    public function getFechaHora() { return $this->fecha_hora; }
    public function setFechaHora($fecha_hora) { $this->fecha_hora = $fecha_hora; }
    
    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
}
