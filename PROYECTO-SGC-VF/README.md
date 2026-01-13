# Sistema de Gestión de Clientes (CRM)

Sistema completo de gestión de relaciones con clientes desarrollado en PHP con arquitectura de 3 capas y **manejo avanzado de excepciones**.

## Características Principales

- Arquitectura por capas (Entidades, Datos, Negocio, Presentación)
- Sistema de autenticación con roles (Admin, Vendedor, Supervisor)
- **Sistema de excepciones personalizado con bloqueo de cuenta por 3 intentos fallidos**
- CRUD completo para: Clientes, Contactos, Oportunidades, Actividades, Productos, Documentos
- Dashboard con estadísticas en tiempo real
- Interfaz responsive con colores corporativos (azul, blanco, verde, rojo)

## Requisitos Previos

- XAMPP (PHP 7.4+ y MySQL 8.0+)
- Visual Studio Code (recomendado)
- Navegador web moderno

## Instalación Paso a Paso

### 1. Instalar XAMPP

1. Descarga XAMPP desde: https://www.apachefriends.org/
2. Ejecuta el instalador y sigue las instrucciones
3. Instala en: `C:\xampp` (Windows) o `/opt/lampp` (Linux)

### 2. Clonar/Copiar el Proyecto

\`\`\`bash
# Copiar el proyecto a la carpeta htdocs de XAMPP
Copiar carpeta completa a: C:\xampp\htdocs\SistemaGestionClientes
\`\`\`

### 3. Crear la Base de Datos

#### Opción A: Desde phpMyAdmin

1. Inicia Apache y MySQL desde XAMPP Control Panel
2. Abre tu navegador en: `http://localhost/phpmyadmin`
3. Crea una nueva base de datos llamada: `bd_clientes`
4. Importa el archivo: `scripts/bd_clientes.sql`
5. **IMPORTANTE:** Importa también: `scripts/bd_clientes_v2_excepciones.sql` (para agregar campos de control de intentos)

#### Opción B: Desde MySQL Command Line

\`\`\`bash
mysql -u root -p
\`\`\`

\`\`\`sql
CREATE DATABASE bd_clientes;
USE bd_clientes;
SOURCE /ruta/completa/scripts/bd_clientes.sql;
SOURCE /ruta/completa/scripts/bd_clientes_v2_excepciones.sql;
\`\`\`

### 4. Configurar Conexión a Base de Datos

Edita el archivo: `CapaDatos/Conexion.php` (si es necesario)

\`\`\`php
private $host = 'localhost';
private $dbname = 'bd_clientes';
private $username = 'root';
private $password = ''; // Tu contraseña de MySQL
private $port = '3306';
\`\`\`

### 5. Ejecutar el Sistema

#### Opción A: Con XAMPP

1. Asegúrate que Apache y MySQL estén corriendo en XAMPP
2. Abre tu navegador en:
   - Si Apache usa puerto 80: `http://localhost/SistemaGestionClientes/CapaPresentacion/index.html`
   - Si Apache usa puerto 1443: `http://localhost:1443/SistemaGestionClientes/CapaPresentacion/index.html`

#### Opción B: Con Visual Studio Code (Recomendado)

1. Abre el proyecto en VS Code
2. Abre la terminal integrada (Ctrl + Ñ o Ctrl + `)
3. Ejecuta:

\`\`\`bash
# En Windows
cd D:\xampp\htdocs\SistemaGestionClientes
php -S localhost:8000

# En Linux/Mac
cd /opt/lampp/htdocs/SistemaGestionClientes
php -S localhost:8000
\`\`\`

4. Abre tu navegador en: `http://localhost:8000/CapaPresentacion/index.html`

**Nota:** Solo necesitas que MySQL esté corriendo en XAMPP, no Apache.

### 6. Iniciar Sesión

**Credenciales por defecto:**
- Email: `admin@sistema.com`
- Contraseña: `admin123`

**Usuarios adicionales de prueba:**
- `juan.perez@sistema.com` / `vendedor123` (Vendedor)
- `maria.gonzalez@sistema.com` / `supervisor123` (Supervisor)

---

## Sistema de Excepciones Personalizado

El sistema implementa **3 excepciones personalizadas** para el login con mensajes diferenciados visualmente:

### 1. UsuarioNoExistenteException
**Se lanza cuando:** El email ingresado no existe en el sistema.
**Color:** Rojo
**Archivo:** `CapaExcepciones/UsuarioNoExistenteException.php`

### 2. ContraseñaIncorrectaException
**Se lanza cuando:** La contraseña es incorrecta. Muestra intentos restantes (3 máximo).
**Color:** Amarillo/Naranja
**Archivo:** `CapaExcepciones/ContraseñaIncorrectaException.php`

### 3. CuentaBloqueadaException
**Se lanza cuando:** La cuenta se bloquea por 3 intentos fallidos consecutivos.
**Color:** Rojo oscuro
**Archivo:** `CapaExcepciones/CuentaBloqueadaException.php`

### Probar el Sistema de Excepciones

**Prueba 1: Usuario no existente**
- Email: `noexiste@test.com`
- Password: `cualquiera`
- Resultado: Ver excepción con mensaje de error rojo

**Prueba 2: Contraseña incorrecta**
- Email: `admin@sistema.com`
- Password: `incorrecta`
- Resultado: Ver excepción amarilla con intentos restantes

**Prueba 3: Cuenta bloqueada**
- Ingresa contraseña incorrecta 3 veces consecutivas
- Resultado: La cuenta se bloquea automáticamente

### Desbloquear una Cuenta

**Desde MySQL:**
\`\`\`sql
UPDATE Usuario 
SET bloqueado = FALSE, intentos_fallidos = 0, fecha_bloqueo = NULL, ultimo_intento = NULL 
WHERE email = 'admin@sistema.com';
\`\`\`

**Desde código PHP (método administrativo):**
\`\`\`php
$usuarioDAO->desbloquearCuenta($usuarioId);
\`\`\`

---

## Estructura del Proyecto

\`\`\`
SistemaGestionClientes/
├── CapaDatos/              # Acceso a base de datos (DAO)
├── CapaEntidades/          # Modelos de datos
├── CapaNegocio/            # Lógica de negocio
├── CapaPresentacion/       # Interfaz HTML/PHP/JS
│   ├── api/                # Endpoints REST
│   ├── css/                # Estilos
│   └── js/                 # JavaScript
├── CapaExcepciones/        # Excepciones personalizadas
├── scripts/                # Scripts SQL
├── DOCUMENTACION_EXCEPCIONES.md  # Documentación detallada de excepciones
└── README.md
\`\`\`

## Módulos del Sistema

1. **Dashboard**: Resumen con estadísticas (clientes, oportunidades, actividades)
2. **Clientes**: Gestión de clientes (empresas o personas)
3. **Contactos**: Personas de contacto por cliente
4. **Oportunidades**: Seguimiento de ventas potenciales
5. **Actividades**: Registro de llamadas, emails, reuniones
6. **Productos**: Catálogo de productos/servicios
7. **Documentos**: Archivo de propuestas y contratos

## Solución de Problemas

### Error: "Connection refused"
- Verifica que MySQL esté corriendo en XAMPP
- Verifica el puerto correcto de Apache (80 o 1443)

### Error: "Base de datos no existe"
- Asegúrate de haber importado ambos scripts SQL
- Verifica el nombre de la base de datos en Conexion.php

### Error: "Cannot find api/login.php"
- Verifica que estés en la carpeta correcta
- Usa la ruta completa: `/CapaPresentacion/index.html`

### Página en blanco o error 500
- Revisa los logs de PHP en: `C:\xampp\php\logs\php_error_log`
- Verifica que todas las clases tengan los `require_once` correctos

### Dashboard muestra 0 en todos los contadores
- Verifica que hayas importado el script SQL con datos de prueba
- Verifica que los archivos API existan en `CapaPresentacion/api/`

## Documentación Adicional

- **DOCUMENTACION_EXCEPCIONES.md**: Documentación completa del sistema de excepciones con ejemplos de código, diagramas de flujo y casos de prueba

## Principios SOLID Implementados

- **Single Responsibility**: Cada clase tiene una única responsabilidad
- **Open/Closed**: Código abierto a extensión, cerrado a modificación
- **Liskov Substitution**: Herencia coherente en entidades
- **Interface Segregation**: Interfaces específicas por módulo
- **Dependency Injection**: Servicios inyectados en capas superiores

## Tecnologías Utilizadas

- PHP 7.4+
- MySQL 8.0+
- HTML5, CSS3, JavaScript (Vanilla)
- PDO para acceso a datos
- Arquitectura MVC por capas
- Sistema de excepciones personalizado

## Colores del Sistema

- **Azul**: `#2563eb` (Principal)
- **Blanco**: `#ffffff`
- **Verde**: `#10b981` (Éxito)
- **Rojo**: `#ef4444` (Error/Peligro)

## Autor

Sistema desarrollado como proyecto académico para demostrar:
- Arquitectura por capas
- Principios SOLID
- Manejo de excepciones en PHP
- Sistema de autenticación seguro con bloqueo de cuenta

## Licencia

Proyecto educativo - Uso libre para fines académicos

---

**GRUPO 1: SISTEMA DE GESTIÓN DE CLIENTES**

Para más detalles sobre el manejo de excepciones, consulta: `DOCUMENTACION_EXCEPCIONES.md`
