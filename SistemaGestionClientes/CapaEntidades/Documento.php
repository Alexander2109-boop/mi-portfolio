<?php
/**
 * Entidad Documento
 */
class Documento {
    private $id;
    private $oportunidad_id;
    private $nombre;
    private $url;
    private $tipo;
    private $fecha_subida;

    public function __construct($id = null, $oportunidad_id = null, $nombre = '', $url = '', $tipo = '', $fecha_subida = null) {
        $this->id = $id;
        $this->oportunidad_id = $oportunidad_id;
        $this->nombre = $nombre;
        $this->url = $url;
        $this->tipo = $tipo;
        $this->fecha_subida = $fecha_subida;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getOportunidadId() { return $this->oportunidad_id; }
    public function setOportunidadId($oportunidad_id) { $this->oportunidad_id = $oportunidad_id; }
    
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    
    public function getUrl() { return $this->url; }
    public function setUrl($url) { $this->url = $url; }
    
    public function getTipo() { return $this->tipo; }
    public function setTipo($tipo) { $this->tipo = $tipo; }
    
    public function getFechaSubida() { return $this->fecha_subida; }
    public function setFechaSubida($fecha_subida) { $this->fecha_subida = $fecha_subida; }
}
