<?php
class UsuarioNoExistenteException extends Exception {
    public function __construct($message = "Usuario no encontrado") {
        parent::__construct($message);
    }
}