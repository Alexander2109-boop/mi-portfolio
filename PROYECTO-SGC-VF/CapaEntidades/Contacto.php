<?php
/**
 * Entidad Contacto
 * Representa un contacto asociado a un cliente dentro del sistema.
 */
class Contacto {
    // Identificador único del contacto
    private $id;

    // ID del cliente al que pertenece este contacto
    private $cliente_id;

    // Nombre completo del contacto
    private $nombre;

    // Cargo o posición del contacto dentro de la empresa
    private $cargo;

    // Correo electrónico del contacto
    private $email;

    // Teléfono del contacto
    private $telefono;

    /**
     * Constructor de la clase Contacto
     * Permite inicializar los atributos con valores predeterminados o enviados
     */
    public function __construct($id = null, $cliente_id = null, $nombre = '', $cargo = '', $email = '', $telefono = '') {
        $this->id = $id;
        $this->cliente_id = $cliente_id;
        $this->nombre = $nombre;
        $this->cargo = $cargo;
        $this->email = $email;
        $this->telefono = $telefono;
    }

    // Getter para obtener el ID del contacto
    public function getId() { return $this->id; }

    // Setter para modificar el ID del contacto
    public function setId($id) { $this->id = $id; }
    
    // Getter para obtener el ID del cliente asociado
    public function getClienteId() { return $this->cliente_id; }

    // Setter para establecer el ID del cliente asociado
    public function setClienteId($cliente_id) { $this->cliente_id = $cliente_id; }
    
    // Getter para obtener el nombre del contacto
    public function getNombre() { return $this->nombre; }

    // Setter para modificar el nombre del contacto
    public function setNombre($nombre) { $this->nombre = $nombre; }
    
    // Getter para obtener el cargo del contacto
    public function getCargo() { return $this->cargo; }

    // Setter para modificar el cargo del contacto
    public function setCargo($cargo) { $this->cargo = $cargo; }
    
    // Getter para obtener el correo electrónico del contacto
    public function getEmail() { return $this->email; }

    // Setter para modificar el correo electrónico del contacto
    public function setEmail($email) { $this->email = $email; }
    
    // Getter para obtener el número de teléfono del contacto
    public function getTelefono() { return $this->telefono; }

    // Setter para modificar el número de teléfono del contacto
    public function setTelefono($telefono) { $this->telefono = $telefono; }
}
