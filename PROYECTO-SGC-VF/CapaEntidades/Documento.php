<?php
/**
 * Entidad Documento
 * Representa un documento asociado a una oportunidad dentro del sistema CRM.
 */
class Documento {
    // Identificador único del documento
    private $id;

    // ID de la oportunidad a la cual este documento pertenece
    private $oportunidad_id;

    // Nombre del archivo o documento
    private $nombre;

    // URL donde está almacenado el documento
    private $url;

    // Tipo de documento (PDF, imagen, Word, etc.)
    private $tipo;

    // Fecha en que fue subido el documento
    private $fecha_subida;

    /**
     * Constructor de la clase Documento
     * Inicializa los valores del documento, pudiendo recibirlos o usar valores predeterminados
     */
    public function __construct($id = null, $oportunidad_id = null, $nombre = '', $url = '', $tipo = '', $fecha_subida = null) {
        $this->id = $id;
        $this->oportunidad_id = $oportunidad_id;
        $this->nombre = $nombre;
        $this->url = $url;
        $this->tipo = $tipo;
        $this->fecha_subida = $fecha_subida;
    }

    // Getter para obtener el ID
    public function getId() { return $this->id; }

    // Setter para modificar el ID
    public function setId($id) { $this->id = $id; }
    
    // Getter para obtener el ID de la oportunidad asociada
    public function getOportunidadId() { return $this->oportunidad_id; }

    // Setter para establecer el ID de la oportunidad asociada
    public function setOportunidadId($oportunidad_id) { $this->oportunidad_id = $oportunidad_id; }
    
    // Getter para obtener el nombre del documento
    public function getNombre() { return $this->nombre; }

    // Setter para modificar el nombre del documento
    public function setNombre($nombre) { $this->nombre = $nombre; }
    
    // Getter para obtener la URL del archivo
    public function getUrl() { return $this->url; }

    // Setter para modificar la URL del archivo
    public function setUrl($url) { $this->url = $url; }
    
    // Getter para obtener el tipo de documento
    public function getTipo() { return $this->tipo; }

    // Setter para modificar el tipo de documento
    public function setTipo($tipo) { $this->tipo = $tipo; }
    
    // Getter para obtener la fecha en que se subió el documento
    public function getFechaSubida() { return $this->fecha_subida; }

    // Setter para modificar la fecha de subida
    public function setFechaSubida($fecha_subida) { $this->fecha_subida = $fecha_subida; }
}
