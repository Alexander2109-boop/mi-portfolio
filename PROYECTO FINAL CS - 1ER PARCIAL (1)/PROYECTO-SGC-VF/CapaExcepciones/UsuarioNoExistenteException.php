<?php

/**
 * CAPA DE EXCEPCIONES - UsuarioNoExistenteException.php
 * 
 * Descripción: Excepción personalizada para usuario no existente
 * Propósito: Lanzar cuando se intenta autenticar con un email no registrado
 * 
 * PRINCIPIO: Manejo de Excepciones Específicas
 * - Hereda de la clase Exception de PHP
 * - Proporciona un mensaje claro y específico del error
 * - Permite captura selectiva en bloques try-catch
 */
class UsuarioNoExistenteException extends Exception {
    
    /**
     * Constructor de la excepción
     * 
     * @param string $email - Email del usuario que no existe
     * @param int $code - Código de error (opcional)
     * @param Exception $previous - Excepción anterior en la cadena (opcional)
     */
    public function __construct($email, $code = 0, Exception $previous = null) {
        // Construir mensaje descriptivo con el email intentado
        $message = "El usuario con email '$email' no existe en el sistema.";
        
        // Llamar al constructor de la clase padre (Exception)
        parent::__construct($message, $code, $previous);
    }

    /**
     * Representación en string de la excepción
     * Útil para logging y debugging
     * 
     * @return string Representación formateada de la excepción
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
