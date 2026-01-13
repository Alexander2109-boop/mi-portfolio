<?php

/**
 * CAPA DE EXCEPCIONES - CuentaBloqueadaException.php
 * 
 * Descripción: Excepción personalizada para cuenta bloqueada
 * Propósito: Lanzar cuando un usuario ha excedido el máximo de intentos fallidos (3)
 * Seguridad: Previene ataques de fuerza bruta bloqueando cuentas temporalmente
 */
class CuentaBloqueadaException extends Exception {
    // Atributos adicionales para tracking del bloqueo
    private $email;           // Email de la cuenta bloqueada
    private $fechaBloqueo;    // Timestamp del bloqueo

    /**
     * Constructor de la excepción
     * 
     * @param string $email - Email del usuario bloqueado
     * @param string $fechaBloqueo - Fecha y hora del bloqueo
     * @param int $code - Código de error (opcional)
     * @param Exception $previous - Excepción anterior (opcional)
     */
    public function __construct($email, $fechaBloqueo, $code = 0, Exception $previous = null) {
        $this->email = $email;
        $this->fechaBloqueo = $fechaBloqueo;
        
        // Mensaje detallado con instrucciones para el usuario
        $message = "La cuenta '$email' ha sido bloqueada por exceder el número máximo de intentos fallidos. " .
                   "Fecha de bloqueo: $fechaBloqueo. Contacte al administrador.";
        
        parent::__construct($message, $code, $previous);
    }

    /**
     * Getter para email bloqueado
     * @return string Email de la cuenta bloqueada
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Getter para fecha de bloqueo
     * @return string Fecha y hora del bloqueo
     */
    public function getFechaBloqueo() {
        return $this->fechaBloqueo;
    }

    /**
     * Representación en string de la excepción
     * @return string Representación formateada
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
