-- Actualización de base de datos: Sistema de Gestión de Clientes
-- Versión 2: Incluye campos para manejo de excepciones y bloqueo de cuenta

USE bd_clientes;

-- Agregar campos para control de intentos fallidos y bloqueo de cuenta
ALTER TABLE Usuario 
ADD COLUMN intentos_fallidos INT DEFAULT 0 COMMENT 'Contador de intentos fallidos de login',
ADD COLUMN bloqueado BOOLEAN DEFAULT FALSE COMMENT 'Indica si la cuenta está bloqueada',
ADD COLUMN fecha_bloqueo TIMESTAMP NULL COMMENT 'Fecha y hora del bloqueo de cuenta',
ADD COLUMN ultimo_intento TIMESTAMP NULL COMMENT 'Fecha del último intento de login';

-- Actualizar usuarios existentes
UPDATE Usuario SET intentos_fallidos = 0, bloqueado = FALSE WHERE intentos_fallidos IS NULL AND id > 0;

-- Índice para mejorar búsquedas por email
CREATE INDEX idx_usuario_email ON Usuario(email);

-- Desbloquear cuenta
UPDATE Usuario SET bloqueado = FALSE, intentos_fallidos = 0 WHERE email = 'admin@sistema.com';
