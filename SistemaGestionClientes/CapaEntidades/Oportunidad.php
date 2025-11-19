<?php
/**
 * Entidad Oportunidad
 */
class Oportunidad {
    private $id;
    private $cliente_id;
    private $estado_oportunidad_id;
    private $fecha_hora;
    private $monto;
    private $descripcion;

    public function __construct($id = null, $cliente_id = null, $estado_oportunidad_id = null, $fecha_hora = null, $monto = 0, $descripcion = '') {
        $this->id = $id;
        $this->cliente_id = $cliente_id;
        $this->estado_oportunidad_id = $estado_oportunidad_id;
        $this->fecha_hora = $fecha_hora;
        $this->monto = $monto;
        $this->descripcion = $descripcion;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getClienteId() { return $this->cliente_id; }
    public function setClienteId($cliente_id) { $this->cliente_id = $cliente_id; }
    
    public function getEstadoOportunidadId() { return $this->estado_oportunidad_id; }
    public function setEstadoOportunidadId($estado_oportunidad_id) { $this->estado_oportunidad_id = $estado_oportunidad_id; }
    
    public function getFechaHora() { return $this->fecha_hora; }
    public function setFechaHora($fecha_hora) { $this->fecha_hora = $fecha_hora; }
    
    public function getMonto() { return $this->monto; }
    public function setMonto($monto) { $this->monto = $monto; }
    
    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
}
