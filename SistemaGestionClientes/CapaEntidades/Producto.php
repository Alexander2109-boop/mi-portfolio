<?php
/**
 * Entidad Producto
 */
class Producto {
    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $activo;

    public function __construct($id = null, $nombre = '', $descripcion = '', $precio = 0, $activo = true) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->activo = $activo;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    
    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    
    public function getPrecio() { return $this->precio; }
    public function setPrecio($precio) { $this->precio = $precio; }
    
    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; }
}
