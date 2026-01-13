# GRUPO 1: SISTEMA DE GESTIÓN DE CLIENTES (CRM)

## Manejo de Excepciones - Ingreso al Sistema

Este documento presenta la implementación completa del sistema de manejo de excepciones para el módulo de autenticación de usuarios.

---

## 1. EXCEPCIONES PERSONALIZADAS CREADAS

### 1.1 UsuarioNoExistenteException

**Archivo:** `CapaExcepciones/UsuarioNoExistenteException.php`

\`\`\`php
<?php

/**
 * Excepción personalizada: Usuario No Existente
 * Se lanza cuando se intenta autenticar con un email que no existe en el sistema
 */
class UsuarioNoExistenteException extends Exception {
    public function __construct($email, $code = 0, Exception $previous = null) {
        $message = "El usuario con email '$email' no existe en el sistema.";
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
\`\`\`

**Cuándo se lanza:** Cuando el usuario intenta iniciar sesión con un email que no está registrado en la base de datos.

---

### 1.2 ContraseñaIncorrectaException

**Archivo:** `CapaExcepciones/ContraseñaIncorrectaException.php`

\`\`\`php
<?php

/**
 * Excepción personalizada: Contraseña Incorrecta
 * Se lanza cuando la contraseña ingresada no coincide con la del usuario
 */
class ContraseñaIncorrectaException extends Exception {
    private $intentosRestantes;

    public function __construct($intentosRestantes, $code = 0, Exception $previous = null) {
        $this->intentosRestantes = $intentosRestantes;
        $message = "Contraseña incorrecta. Intentos restantes: $intentosRestantes";
        parent::__construct($message, $code, $previous);
    }

    public function getIntentosRestantes() {
        return $this->intentosRestantes;
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
\`\`\`

**Cuándo se lanza:** Cuando el usuario existe pero la contraseña ingresada es incorrecta. Incluye información sobre los intentos restantes antes del bloqueo.

---

### 1.3 CuentaBloqueadaException

**Archivo:** `CapaExcepciones/CuentaBloqueadaException.php`

\`\`\`php
<?php

/**
 * Excepción personalizada: Cuenta Bloqueada
 * Se lanza cuando el usuario ha excedido el número máximo de intentos fallidos (3)
 */
class CuentaBloqueadaException extends Exception {
    private $email;
    private $fechaBloqueo;

    public function __construct($email, $fechaBloqueo, $code = 0, Exception $previous = null) {
        $this->email = $email;
        $this->fechaBloqueo = $fechaBloqueo;
        $message = "La cuenta '$email' ha sido bloqueada por exceder el número máximo de intentos fallidos. Fecha de bloqueo: $fechaBloqueo. Contacte al administrador.";
        parent::__construct($message, $code, $previous);
    }

    public function getEmail() {
        return $this->email;
    }

    public function getFechaBloqueo() {
        return $this->fechaBloqueo;
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
\`\`\`

**Cuándo se lanza:** 
- Cuando el usuario ha fallado 3 intentos consecutivos de login
- Cuando intenta acceder a una cuenta previamente bloqueada

---

## 2. LANZAMIENTO DE EXCEPCIONES

### 2.1 Método autenticar() en UsuarioDAO

**Archivo:** `CapaDatos/UsuarioDAO.php`

\`\`\`php
/**
 * Autenticar usuario con manejo de excepciones y control de bloqueos
 * 
 * @throws UsuarioNoExistenteException Si el email no existe
 * @throws CuentaBloqueadaException Si la cuenta está bloqueada
 * @throws ContraseñaIncorrectaException Si la contraseña es incorrecta
 */
public function autenticar($email, $password) {
    // Paso 1: Verificar si el usuario existe
    $usuario = $this->obtenerPorEmail($email);
    
    if (!$usuario) {
        // LANZAR EXCEPCIÓN: Usuario no existente
        throw new UsuarioNoExistenteException($email);
    }

    // Paso 2: Verificar si la cuenta está bloqueada
    if ($usuario['bloqueado'] == 1) {
        // LANZAR EXCEPCIÓN: Cuenta bloqueada
        throw new CuentaBloqueadaException($email, $usuario['fecha_bloqueo']);
    }

    // Paso 3: Verificar la contraseña
    if ($usuario['password'] !== $password) {
        // Incrementar intentos fallidos
        $this->incrementarIntentosFallidos($usuario['id']);
        
        // Verificar si se alcanzó el límite de intentos
        $intentosActuales = $usuario['intentos_fallidos'] + 1;
        
        if ($intentosActuales >= self::MAX_INTENTOS) {
            // Bloquear la cuenta
            $this->bloquearCuenta($usuario['id']);
            throw new CuentaBloqueadaException($email, date('Y-m-d H:i:s'));
        }
        
        $intentosRestantes = self::MAX_INTENTOS - $intentosActuales;
        
        // LANZAR EXCEPCIÓN: Contraseña incorrecta
        throw new ContraseñaIncorrectaException($intentosRestantes);
    }

    // Paso 4: Login exitoso - reiniciar contador de intentos
    $this->reiniciarIntentosFallidos($usuario['id']);

    return new Usuario(
        $usuario['id'],
        $usuario['nombre'],
        $usuario['email'],
        $usuario['password'],
        $usuario['rol_id'],
        $usuario['activo']
    );
}
\`\`\`

---

## 3. CAPTURA DE EXCEPCIONES

### 3.1 API de Login

**Archivo:** `CapaPresentacion/api/login.php`

\`\`\`php
try {
    // Intentar autenticar - AQUÍ SE CAPTURAN LAS EXCEPCIONES
    $usuarioDAO = new UsuarioDAO();
    $usuario = $usuarioDAO->autenticar($email, $password);

    // Si llega aquí, el login fue exitoso
    $_SESSION['usuario_id'] = $usuario->getId();
    $_SESSION['usuario_nombre'] = $usuario->getNombre();
    $_SESSION['usuario_email'] = $usuario->getEmail();
    $_SESSION['usuario_rol'] = $usuario->getRolId();

    echo json_encode([
        'success' => true,
        'message' => 'Login exitoso',
        'usuario' => [
            'id' => $usuario->getId(),
            'nombre' => $usuario->getNombre(),
            'email' => $usuario->getEmail(),
            'rol' => $usuario->getRolId()
        ]
    ]);

} catch (UsuarioNoExistenteException $e) {
    // CAPTURA: Usuario no existe
    echo json_encode([
        'success' => false,
        'tipo_error' => 'USUARIO_NO_EXISTENTE',
        'message' => $e->getMessage(),
        'detalles' => 'El email ingresado no está registrado en el sistema.'
    ]);

} catch (CuentaBloqueadaException $e) {
    // CAPTURA: Cuenta bloqueada por intentos fallidos
    echo json_encode([
        'success' => false,
        'tipo_error' => 'CUENTA_BLOQUEADA',
        'message' => $e->getMessage(),
        'email' => $e->getEmail(),
        'fecha_bloqueo' => $e->getFechaBloqueo(),
        'detalles' => 'Su cuenta ha sido bloqueada por seguridad. Contacte al administrador.'
    ]);

} catch (ContraseñaIncorrectaException $e) {
    // CAPTURA: Contraseña incorrecta
    echo json_encode([
        'success' => false,
        'tipo_error' => 'CONTRASEÑA_INCORRECTA',
        'message' => $e->getMessage(),
        'intentos_restantes' => $e->getIntentosRestantes(),
        'detalles' => 'La contraseña ingresada es incorrecta.'
    ]);

} catch (Exception $e) {
    // CAPTURA: Cualquier otra excepción no prevista
    echo json_encode([
        'success' => false,
        'tipo_error' => 'ERROR_INTERNO',
        'message' => 'Error interno del servidor',
        'detalles' => $e->getMessage()
    ]);
}
\`\`\`

---

## 4. ESTRUCTURA DE BASE DE DATOS

### 4.1 Campos agregados a la tabla Usuario

\`\`\`sql
ALTER TABLE Usuario 
ADD COLUMN intentos_fallidos INT DEFAULT 0 COMMENT 'Contador de intentos fallidos',
ADD COLUMN bloqueado BOOLEAN DEFAULT FALSE COMMENT 'Indica si la cuenta está bloqueada',
ADD COLUMN fecha_bloqueo TIMESTAMP NULL COMMENT 'Fecha y hora del bloqueo',
ADD COLUMN ultimo_intento TIMESTAMP NULL COMMENT 'Fecha del último intento de login';
\`\`\`

---

## 5. FLUJO DEL SISTEMA

### Diagrama de Flujo:

\`\`\`
Inicio Login
    ↓
¿Usuario existe? → NO → [UsuarioNoExistenteException]
    ↓ SÍ
¿Cuenta bloqueada? → SÍ → [CuentaBloqueadaException]
    ↓ NO
¿Contraseña correcta? → NO → Incrementar intentos
    ↓                              ↓
   SÍ                    ¿intentos >= 3? → SÍ → [CuentaBloqueadaException]
    ↓                              ↓ NO
Reiniciar intentos        [ContraseñaIncorrectaException]
    ↓
Login Exitoso
\`\`\`

---

## 6. PRUEBAS DEL SISTEMA

### 6.1 Caso de Prueba: Usuario No Existente

**Entrada:**
- Email: `noexiste@test.com`
- Password: `cualquiera`

**Resultado Esperado:**
- Excepción: `UsuarioNoExistenteException`
- Mensaje: "El usuario con email 'noexiste@test.com' no existe en el sistema."
- Color: Rojo

---

### 6.2 Caso de Prueba: Contraseña Incorrecta

**Entrada:**
- Email: `admin@sistema.com`
- Password: `incorrecta`

**Resultado Esperado:**
- Excepción: `ContraseñaIncorrectaException`
- Mensaje: "Contraseña incorrecta. Intentos restantes: 2"
- Color: Amarillo

---

### 6.3 Caso de Prueba: Cuenta Bloqueada

**Entrada:**
- Email: `admin@sistema.com`
- Password incorrecta (3 veces)

**Resultado Esperado:**
- Excepción: `CuentaBloqueadaException`
- Mensaje: "La cuenta ha sido bloqueada por exceder el número máximo de intentos fallidos"
- Color: Rojo oscuro
- Campo `bloqueado` = TRUE en base de datos

---

## 7. PRINCIPIOS APLICADOS

1. **Separación de Responsabilidades**: Cada excepción tiene una clase dedicada
2. **Encapsulación**: Las excepciones contienen información específica del error
3. **Manejo Centralizado**: Todas las excepciones se capturan en un solo punto (API)
4. **Experiencia de Usuario**: Mensajes claros y diferenciados por color
5. **Seguridad**: Sistema de bloqueo automático tras 3 intentos fallidos

---

## 8. DESBLOQUEO DE CUENTAS (Método Administrativo)

\`\`\`php
/**
 * Desbloquear cuenta (método administrativo)
 */
public function desbloquearCuenta($usuarioId) {
    $sql = "UPDATE Usuario 
            SET bloqueado = FALSE, 
                intentos_fallidos = 0, 
                fecha_bloqueo = NULL, 
                ultimo_intento = NULL 
            WHERE id = :id";
    $stmt = $this->conexion->prepare($sql);
    return $stmt->execute([':id' => $usuarioId]);
}
\`\`\`

---

## CONCLUSIÓN

El sistema implementa un manejo robusto de excepciones que:
- Proporciona mensajes específicos para cada tipo de error
- Protege las cuentas mediante bloqueo automático
- Mejora la experiencia del usuario con feedback visual diferenciado
- Sigue las mejores prácticas de desarrollo orientado a objetos
- Implementa seguridad adicional en el proceso de autenticación
