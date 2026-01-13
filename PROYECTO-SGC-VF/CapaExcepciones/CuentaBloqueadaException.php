<?php

class CuentaBloqueadaException extends Exception {
    private $email;
    private $fechaBloqueo;

    public function __construct($message, $email, $fechaBloqueo) {
        parent::__construct($message);
        $this->email = $email;
        $this->fechaBloqueo = $fechaBloqueo;
    }

    public function getEmail() {
        return $this->email;
    }

    // Este es el método que te falta:
    public function getFechaBloqueo() {
        return $this->fechaBloqueo;
    }
}