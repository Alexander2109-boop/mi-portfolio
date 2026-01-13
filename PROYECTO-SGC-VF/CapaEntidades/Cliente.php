<?php
/**
 * CAPA DE ENTIDADES - Cliente.php
 * Descripción: Clase que representa la entidad Cliente
 * Propósito: Encapsular los datos de un cliente del sistema
 * Patrón de Diseño: Entidad de Datos (Data Entity)
 */
class Cliente {
    // Atributos privados del cliente
    private $id;                  // Identificador único
    private $nombre;              // Nombre o razón social del cliente
    private $tipo_cliente_id;     // FK a TipoCliente (Persona/Empresa)
    private $telefono;            // Número de contacto
    private $direccion;           // Dirección física
    private $fecha_alta;          // Fecha de registro en el sistema
    private $activo;              // Estado (activo/inactivo)

    /**
     * Constructor de la clase Cliente
     * @param int|null $id - Identificador único del cliente
     * @param string $nombre - Nombre o razón social
     * @param int|null $tipo_cliente_id - Tipo de cliente (1=Persona, 2=Empresa)
     * @param string $telefono - Teléfono de contacto
     * @param string $direccion - Dirección completa
     * @param string|null $fecha_alta - Fecha de alta en formato Y-m-d
     * @param bool $activo - Estado del cliente
     */
    public function __construct($id = null, $nombre = '', $tipo_cliente_id = null, $telefono = '', $direccion = '', $fecha_alta = null, $activo = true) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo_cliente_id = $tipo_cliente_id;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->fecha_alta = $fecha_alta;
        $this->activo = $activo;
    }

    // Getters - Acceso de solo lectura a los atributos
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getTipoClienteId() { return $this->tipo_cliente_id; }
    public function getTelefono() { return $this->telefono; }
    public function getDireccion() { return $this->direccion; }
    public function getFechaAlta() { return $this->fecha_alta; }
    public function getActivo() { return $this->activo; }

    // Setters - Modificación controlada de los atributos
    public function setId($id) { $this->id = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setTipoClienteId($tipo_cliente_id) { $this->tipo_cliente_id = $tipo_cliente_id; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
    public function setDireccion($direccion) { $this->direccion = $direccion; }
    public function setFechaAlta($fecha_alta) { $this->fecha_alta = $fecha_alta; }
    public function setActivo($activo) { $this->activo = $activo; }
}
