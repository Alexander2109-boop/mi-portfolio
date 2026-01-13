<?php
/**
 * Entidad Oportunidad
 * Representa una oportunidad de negocio asociada a un cliente.
 */
class Oportunidad {
    // Identificador único de la oportunidad
    private $id;

    // ID del cliente al que pertenece la oportunidad
    private $cliente_id;

    // ID del estado actual de la oportunidad (ej.: abierta, ganada, perdida)
    private $estado_oportunidad_id;

    // Fecha y hora en que se registró o agendó la oportunidad
    private $fecha_hora;

    // Monto estimado o valor económico de la oportunidad
    private $monto;

    // Descripción detallada de la oportunidad
    private $descripcion;

    /**
     * Constructor de la clase Oportunidad
     * Permite inicializar los atributos de una oportunidad
     */
    public function __construct($id = null, $cliente_id = null, $estado_oportunidad_id = null, $fecha_hora = null, $monto = 0, $descripcion = '') {
        $this->id = $id;
        $this->cliente_id = $cliente_id;
        $this->estado_oportunidad_id = $estado_oportunidad_id;
        $this->fecha_hora = $fecha_hora;
        $this->monto = $monto;
        $this->descripcion = $descripcion;
    }

    // Getter para obtener el ID de la oportunidad
    public function getId() { return $this->id; }

    // Setter para modificar el ID de la oportunidad
    public function setId($id) { $this->id = $id; }
    
    // Getter para obtener el ID del cliente asociado
    public function getClienteId() { return $this->cliente_id; }

    // Setter para establecer el ID del cliente asociado
    public function setClienteId($cliente_id) { $this->cliente_id = $cliente_id; }
    
    // Getter para obtener el estado actual de la oportunidad
    public function getEstadoOportunidadId() { return $this->estado_oportunidad_id; }

    // Setter para modificar el estado de la oportunidad
    public function setEstadoOportunidadId($estado_oportunidad_id) { $this->estado_oportunidad_id = $estado_oportunidad_id; }
    
    // Getter para obtener la fecha y hora de la oportunidad
    public function getFechaHora() { return $this->fecha_hora; }

    // Setter para modificar la fecha y hora de la oportunidad
    public function setFechaHora($fecha_hora) { $this->fecha_hora = $fecha_hora; }
    
    // Getter para obtener el monto asociado a la oportunidad
    public function getMonto() { return $this->monto; }

    // Setter para modificar el monto de la oportunidad
    public function setMonto($monto) { $this->monto = $monto; }
    
    // Getter para obtener la descripción de la oportunidad
    public function getDescripcion() { return $this->descripcion; }

    // Setter para modificar la descripción de la oportunidad
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
}
