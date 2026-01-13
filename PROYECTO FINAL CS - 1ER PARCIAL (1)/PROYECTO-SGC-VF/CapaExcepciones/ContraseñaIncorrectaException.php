<?php

/**
 * CAPA DE EXCEPCIONES - ContraseñaIncorrectaException.php
 * 
 * Descripción: Excepción personalizada para contraseña incorrecta
 * Propósito: Lanzar cuando la contraseña no coincide con la del usuario
 * Característica: Incluye contador de intentos restantes antes del bloqueo
 */
class ContraseñaIncorrectaException extends Exception {
    // Atributo adicional: intentos restantes antes del bloqueo
    private $intentosRestantes;

    /**
     * Constructor de la excepción
     * 
     * @param int $intentosRestantes - Número de intentos que quedan antes del bloqueo
     * @param int $code - Código de error (opcional)
     * @param Exception $previous - Excepción anterior (opcional)
     */
    public function __construct($intentosRestantes, $code = 0, Exception $previous = null) {
        $this->intentosRestantes = $intentosRestantes;
        
        // Mensaje informativo con intentos restantes
        $message = "Contraseña incorrecta. Intentos restantes: $intentosRestantes";
        
        parent::__construct($message, $code, $previous);
    }

    /**
     * Getter para obtener intentos restantes
     * Permite al código que captura la excepción mostrar esta información al usuario
     * 
     * @return int Número de intentos restantes
     */
    public function getIntentosRestantes() {
        return $this->intentosRestantes;
    }

    /**
     * Representación en string de la excepción
     * 
     * @return string Formato: NombreClase: [código]: mensaje
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
