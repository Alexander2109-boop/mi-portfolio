<?php
/**
 * Entidad Actividad
 * Representa una actividad asociada a una oportunidad dentro del sistema.
 */
class Actividad {
    // Identificador único de la actividad
    private $id;

    // ID de la oportunidad a la que pertenece la actividad
    private $oportunidad_id;

    // ID del tipo de actividad (llamada, reunión, correo, etc.)
    private $tipo_actividad_id;

    // Fecha y hora en que se realizará o realizó la actividad
    private $fecha_hora;

    // Descripción de la actividad
    private $descripcion;

    /**
     * Constructor de la clase Actividad
     * Permite inicializar la entidad con valores opcionales
     */
    public function __construct($id = null, $oportunidad_id = null, $tipo_actividad_id = null, $fecha_hora = null, $descripcion = '') {
        $this->id = $id;
        $this->oportunidad_id = $oportunidad_id;
        $this->tipo_actividad_id = $tipo_actividad_id;
        $this->fecha_hora = $fecha_hora;
        $this->descripcion = $descripcion;
    }

    // Getter para el ID
    public function getId() { return $this->id; }

    // Setter para el ID
    public function setId($id) { $this->id = $id; }
    
    // Getter del ID de la oportunidad
    public function getOportunidadId() { return $this->oportunidad_id; }

    // Setter del ID de la oportunidad
    public function setOportunidadId($oportunidad_id) { $this->oportunidad_id = $oportunidad_id; }
    
    // Getter del tipo de actividad
    public function getTipoActividadId() { return $this->tipo_actividad_id; }

    // Setter del tipo de actividad
    public function setTipoActividadId($tipo_actividad_id) { $this->tipo_actividad_id = $tipo_actividad_id; }
    
    // Getter de la fecha y hora
    public function getFechaHora() { return $this->fecha_hora; }

    // Setter de la fecha y hora
    public function setFechaHora($fecha_hora) { $this->fecha_hora = $fecha_hora; }
    
    // Getter de la descripción de la actividad
    public function getDescripcion() { return $this->descripcion; }

    // Setter de la descripción
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
}
