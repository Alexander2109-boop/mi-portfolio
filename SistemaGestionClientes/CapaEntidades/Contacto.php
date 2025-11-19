<?php
/**
 * Entidad Contacto
 */
class Contacto {
    private $id;
    private $cliente_id;
    private $nombre;
    private $cargo;
    private $email;
    private $telefono;

    public function __construct($id = null, $cliente_id = null, $nombre = '', $cargo = '', $email = '', $telefono = '') {
        $this->id = $id;
        $this->cliente_id = $cliente_id;
        $this->nombre = $nombre;
        $this->cargo = $cargo;
        $this->email = $email;
        $this->telefono = $telefono;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getClienteId() { return $this->cliente_id; }
    public function setClienteId($cliente_id) { $this->cliente_id = $cliente_id; }
    
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    
    public function getCargo() { return $this->cargo; }
    public function setCargo($cargo) { $this->cargo = $cargo; }
    
    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }
    
    public function getTelefono() { return $this->telefono; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
}
