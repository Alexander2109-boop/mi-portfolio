<?php
/**
 * Entidad Usuario - Representa un usuario del sistema
 */
class Usuario {
    private $id;
    private $nombre;
    private $email;
    private $password;
    private $rol_id;
    private $activo;

    public function __construct($id = null, $nombre = '', $email = '', $password = '', $rol_id = null, $activo = true) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->rol_id = $rol_id;
        $this->activo = $activo;
    }

    // Getters y Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    
    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }
    
    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }
    
    public function getRolId() { return $this->rol_id; }
    public function setRolId($rol_id) { $this->rol_id = $rol_id; }
    
    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; }
}
