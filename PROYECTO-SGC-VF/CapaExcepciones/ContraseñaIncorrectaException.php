<?php

class ContraseñaIncorrectaException extends Exception {
    private $intentosRestantes;

    public function __construct($message, $intentosRestantes) {
        parent::__construct($message);
        $this->intentosRestantes = $intentosRestantes;
    }

    // Este es el método que te falta:
    public function getIntentosRestantes() {
        return $this->intentosRestantes;
    }
}