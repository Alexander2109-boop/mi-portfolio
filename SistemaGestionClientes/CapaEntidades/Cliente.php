<?php
/**
 * Entidad Cliente
 */
class Cliente {
    private $id;
    private $nombre;
    private $tipo_cliente_id;
    private $telefono;
    private $direccion;
    private $fecha_alta;
    private $activo;

    public function __construct($id = null, $nombre = '', $tipo_cliente_id = null, $telefono = '', $direccion = '', $fecha_alta = null, $activo = true) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo_cliente_id = $tipo_cliente_id;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->fecha_alta = $fecha_alta;
        $this->activo = $activo;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    
    public function getTipoClienteId() { return $this->tipo_cliente_id; }
    public function setTipoClienteId($tipo_cliente_id) { $this->tipo_cliente_id = $tipo_cliente_id; }
    
    public function getTelefono() { return $this->telefono; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
    
    public function getDireccion() { return $this->direccion; }
    public function setDireccion($direccion) { $this->direccion = $direccion; }
    
    public function getFechaAlta() { return $this->fecha_alta; }
    public function setFechaAlta($fecha_alta) { $this->fecha_alta = $fecha_alta; }
    
    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; }
}
