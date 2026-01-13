# PLAN DE PRUEBAS - SISTEMA DE GESTIÓN DE CLIENTES (CRM)

## 1. INTRODUCCIÓN

### 1.1 Propósito
Este documento describe el plan de pruebas para verificar la funcionalidad, seguridad y rendimiento del Sistema de Gestión de Clientes (CRM) desarrollado con arquitectura de tres capas en PHP.

### 1.2 Alcance
El plan cubre todas las funcionalidades del sistema incluyendo autenticación, manejo de excepciones, operaciones CRUD y validaciones de seguridad.

---

## 2. ESTRATEGIA DE PRUEBAS

### 2.1 Tipos de Pruebas
- **Pruebas Funcionales**: Verificar que cada módulo cumple con los requisitos
- **Pruebas de Seguridad**: Validar autenticación, sesiones y bloqueo de cuentas
- **Pruebas de Integración**: Verificar la comunicación entre capas
- **Pruebas de Excepciones**: Validar el manejo correcto de errores

### 2.2 Herramientas
- Navegador Web (Chrome/Firefox/Edge)
- phpMyAdmin para verificación de base de datos
- Visual Studio Code para revisión de logs
- Consola del navegador (F12) para debugging

---

## 3. CASOS DE PRUEBA

### 3.1 MÓDULO DE AUTENTICACIÓN

#### CP-AUTH-001: Login Exitoso
**Objetivo**: Verificar que un usuario registrado puede iniciar sesión correctamente

**Precondiciones**:
- Base de datos poblada con usuario de prueba
- Sistema en ejecución

**Datos de Prueba**:
\`\`\`
Email: admin@sistema.com
Contraseña: admin123
\`\`\`

**Pasos**:
1. Abrir http://localhost:8000/CapaPresentacion/index.html
2. Ingresar email: admin@sistema.com
3. Ingresar contraseña: admin123
4. Click en botón "Iniciar Sesión"

**Resultado Esperado**:
- Redirección a dashboard.php
- Mensaje de bienvenida con nombre de usuario
- Sesión creada correctamente
- Campo intentos_fallidos en BD = 0

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

#### CP-AUTH-002: Usuario No Existente
**Objetivo**: Verificar que el sistema lanza la excepción UsuarioNoExistenteException

**Datos de Prueba**:
\`\`\`
Email: usuario_no_existe@test.com
Contraseña: cualquier123
\`\`\`

**Pasos**:
1. Ingresar email que no existe en BD
2. Ingresar cualquier contraseña
3. Click en "Iniciar Sesión"

**Resultado Esperado**:
- Excepción: UsuarioNoExistenteException capturada
- Mensaje: "El usuario no existe en el sistema"
- Código HTTP: 404
- No se incrementa intentos_fallidos
- Permanece en página de login

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

#### CP-AUTH-003: Contraseña Incorrecta (Intento 1)
**Objetivo**: Verificar que el sistema lanza ContraseñaIncorrectaException y registra intento

**Datos de Prueba**:
\`\`\`
Email: admin@sistema.com
Contraseña: contraseña_incorrecta
\`\`\`

**Pasos**:
1. Ingresar email válido
2. Ingresar contraseña incorrecta
3. Click en "Iniciar Sesión"

**Resultado Esperado**:
- Excepción: ContraseñaIncorrectaException capturada
- Mensaje: "Contraseña incorrecta. Intentos restantes: 2"
- Código HTTP: 401
- BD: intentos_fallidos = 1
- Permanece en página de login

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

#### CP-AUTH-004: Contraseña Incorrecta (Intento 2)
**Objetivo**: Verificar incremento de intentos fallidos

**Datos de Prueba**:
\`\`\`
Email: admin@sistema.com
Contraseña: otra_incorrecta
\`\`\`

**Pasos**:
1. Repetir login con contraseña incorrecta (segunda vez)

**Resultado Esperado**:
- Mensaje: "Contraseña incorrecta. Intentos restantes: 1"
- BD: intentos_fallidos = 2
- cuenta_bloqueada = 0 (aún no bloqueada)

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

#### CP-AUTH-005: Bloqueo de Cuenta (Intento 3)
**Objetivo**: Verificar que la cuenta se bloquea después del tercer intento fallido

**Datos de Prueba**:
\`\`\`
Email: admin@sistema.com
Contraseña: tercera_incorrecta
\`\`\`

**Pasos**:
1. Intentar login con contraseña incorrecta (tercera vez)

**Resultado Esperado**:
- Mensaje: "Contraseña incorrecta. Su cuenta ha sido bloqueada por seguridad. Contacte al administrador."
- BD: intentos_fallidos = 3
- BD: cuenta_bloqueada = 1
- Código HTTP: 403

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

#### CP-AUTH-006: Intento de Login con Cuenta Bloqueada
**Objetivo**: Verificar que no se permite login con cuenta bloqueada incluso con contraseña correcta

**Datos de Prueba**:
\`\`\`
Email: admin@sistema.com (cuenta bloqueada)
Contraseña: admin123 (contraseña correcta)
\`\`\`

**Pasos**:
1. Intentar login con cuenta previamente bloqueada
2. Usar contraseña CORRECTA

**Resultado Esperado**:
- Excepción: CuentaBloqueadaException capturada
- Mensaje: "Su cuenta está bloqueada. Contacte al administrador."
- Código HTTP: 403
- No se permite acceso aunque la contraseña sea correcta

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

#### CP-AUTH-007: Desbloqueo de Cuenta
**Objetivo**: Verificar que el administrador puede desbloquear cuentas

**Pasos**:
1. Ejecutar en phpMyAdmin:
\`\`\`sql
UPDATE Usuario 
SET cuenta_bloqueada = 0, intentos_fallidos = 0 
WHERE email = 'admin@sistema.com';
\`\`\`
2. Intentar login nuevamente con credenciales correctas

**Resultado Esperado**:
- Login exitoso después de desbloqueo
- Acceso al dashboard

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

### 3.2 MÓDULO DE REGISTRO

#### CP-REG-001: Registro de Usuario Exitoso
**Objetivo**: Verificar que un nuevo usuario puede registrarse

**Datos de Prueba**:
\`\`\`
Nombre: Juan Pérez
Email: juan.perez@test.com
Contraseña: test123
Rol: Vendedor
\`\`\`

**Pasos**:
1. Click en "Registrarse" desde login
2. Llenar formulario con datos de prueba
3. Click en "Registrar"

**Resultado Esperado**:
- Usuario creado en BD
- Mensaje de éxito
- Redirección a login
- intentos_fallidos = 0
- cuenta_bloqueada = 0

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

#### CP-REG-002: Registro con Email Duplicado
**Objetivo**: Verificar validación de email único

**Datos de Prueba**:
\`\`\`
Email: admin@sistema.com (ya existe)
\`\`\`

**Pasos**:
1. Intentar registrar con email existente

**Resultado Esperado**:
- Error: "El email ya está registrado"
- No se crea registro duplicado

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

### 3.3 MÓDULO DE CLIENTES

#### CP-CLI-001: Crear Cliente
**Objetivo**: Verificar creación de nuevo cliente

**Datos de Prueba**:
\`\`\`
Nombre: Empresa XYZ S.A.
Tipo: EMPRESA
Teléfono: 555-1234
Email: contacto@xyz.com
Dirección: Av. Principal 123
Estado: ACTIVO
\`\`\`

**Pasos**:
1. Login exitoso
2. Navegar a "Clientes"
3. Click en "Nuevo Cliente"
4. Llenar formulario
5. Click en "Guardar"

**Resultado Esperado**:
- Cliente creado en BD
- Aparece en listado de clientes
- Mensaje de confirmación

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

#### CP-CLI-002: Editar Cliente
**Objetivo**: Verificar actualización de datos de cliente

**Pasos**:
1. Seleccionar cliente existente
2. Click en "Editar"
3. Modificar teléfono a: 555-9999
4. Click en "Guardar"

**Resultado Esperado**:
- Datos actualizados en BD
- Cambios reflejados en listado

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

#### CP-CLI-003: Eliminar Cliente
**Objetivo**: Verificar eliminación de cliente

**Pasos**:
1. Seleccionar cliente de prueba
2. Click en "Eliminar"
3. Confirmar eliminación

**Resultado Esperado**:
- Registro eliminado de BD
- Ya no aparece en listado

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

### 3.4 MÓDULO DE CONTACTOS

#### CP-CON-001: Crear Contacto Asociado a Cliente
**Objetivo**: Verificar creación de contacto vinculado a cliente

**Datos de Prueba**:
\`\`\`
Cliente: Empresa XYZ S.A. (seleccionar de dropdown)
Nombre: María González
Tipo: Gerente de Compras
Email: maria@xyz.com
Teléfono: 555-5678
\`\`\`

**Pasos**:
1. Navegar a "Contactos"
2. Click en "Nuevo Contacto"
3. Seleccionar cliente del dropdown
4. Llenar datos
5. Click en "Guardar"

**Resultado Esperado**:
- Contacto creado con FK a cliente
- Aparece en listado mostrando nombre del cliente

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

#### CP-CON-002: Validar Cliente Obligatorio
**Objetivo**: Verificar que no se puede crear contacto sin cliente

**Pasos**:
1. Intentar crear contacto sin seleccionar cliente

**Resultado Esperado**:
- Error: "Debe seleccionar un cliente"

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

### 3.5 MÓDULO DE OPORTUNIDADES

#### CP-OPO-001: Crear Oportunidad
**Objetivo**: Verificar creación de oportunidad de negocio

**Datos de Prueba**:
\`\`\`
Estado Cliente: ACTIVO (del dropdown)
Tipo Actividad: REUNION
Nombre: Venta de Software ERP
Descripción: Propuesta para implementación de ERP
Valor: 50000.00
Fecha Cierre: 2024-12-31
Estado: EN PROCESO
\`\`\`

**Pasos**:
1. Navegar a "Oportunidades"
2. Click en "Nueva Oportunidad"
3. Llenar formulario
4. Guardar

**Resultado Esperado**:
- Oportunidad creada
- Visible en listado

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

### 3.6 MÓDULO DE ACTIVIDADES

#### CP-ACT-001: Crear Actividad Vinculada a Oportunidad
**Objetivo**: Verificar registro de actividad comercial

**Datos de Prueba**:
\`\`\`
Oportunidad: Venta de Software ERP
Tipo Actividad: LLAMADA
Fecha/Hora: 2024-01-15 10:00:00
Descripción: Seguimiento post-presentación
\`\`\`

**Pasos**:
1. Navegar a "Actividades"
2. Nueva actividad
3. Seleccionar oportunidad
4. Llenar datos
5. Guardar

**Resultado Esperado**:
- Actividad registrada con FK a oportunidad
- Visible en listado

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

### 3.7 MÓDULO DE PRODUCTOS

#### CP-PRO-001: Crear Producto
**Objetivo**: Verificar creación de producto/servicio

**Datos de Prueba**:
\`\`\`
Nombre: Licencia Premium Anual
Descripción: Software ERP módulo completo
Precio: 5000.00
Activo: true
\`\`\`

**Pasos**:
1. Navegar a "Productos"
2. Nuevo producto
3. Llenar datos
4. Guardar

**Resultado Esperado**:
- Producto creado
- Precio formateado correctamente

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

### 3.8 MÓDULO DE DOCUMENTOS

#### CP-DOC-001: Crear Documento Vinculado a Oportunidad
**Objetivo**: Verificar registro de documento

**Datos de Prueba**:
\`\`\`
Oportunidad: Venta de Software ERP (del dropdown)
Nombre: Propuesta Comercial 2024
URL: https://drive.google.com/doc123
Tipo: PDF
\`\`\`

**Pasos**:
1. Navegar a "Documentos"
2. Nuevo documento
3. Seleccionar oportunidad del dropdown
4. Llenar datos
5. Guardar

**Resultado Esperado**:
- Documento creado con FK a oportunidad
- Dropdown muestra oportunidades disponibles
- Visible en listado

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

#### CP-DOC-002: Validar Dropdown de Oportunidades
**Objetivo**: Verificar que el dropdown carga oportunidades correctamente

**Pasos**:
1. Abrir formulario de nuevo documento
2. Verificar dropdown "Oportunidad"

**Resultado Esperado**:
- Dropdown poblado con oportunidades de la BD
- Muestra nombre de cada oportunidad
- No muestra "Seleccione una oportunidad" como única opción

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

### 3.9 MÓDULO DE DASHBOARD

#### CP-DASH-001: Visualización de Estadísticas
**Objetivo**: Verificar que el dashboard muestra contadores correctos

**Pasos**:
1. Login exitoso
2. Observar dashboard

**Resultado Esperado**:
- Muestra total de clientes (> 0)
- Muestra total de oportunidades (> 0)
- Muestra total de actividades (> 0)
- Nombre del usuario logueado visible

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

### 3.10 PRUEBAS DE SEGURIDAD

#### CP-SEC-001: Acceso sin Sesión
**Objetivo**: Verificar que no se puede acceder a páginas protegidas sin login

**Pasos**:
1. Sin hacer login, intentar acceder directamente a:
   - http://localhost:8000/CapaPresentacion/dashboard.php
   - http://localhost:8000/CapaPresentacion/clientes.php

**Resultado Esperado**:
- Redirección automática a index.html
- Mensaje: "Debe iniciar sesión"

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

#### CP-SEC-002: Protección contra SQL Injection
**Objetivo**: Verificar uso de prepared statements

**Datos de Prueba**:
\`\`\`
Email: admin@sistema.com' OR '1'='1
Contraseña: cualquier
\`\`\`

**Pasos**:
1. Intentar login con SQL injection en email

**Resultado Esperado**:
- Login fallido
- Excepción: UsuarioNoExistenteException
- No se compromete la seguridad

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

#### CP-SEC-003: Validación de Datos en Capa de Negocio
**Objetivo**: Verificar que la capa de negocio valida datos antes de enviar a BD

**Pasos**:
1. Intentar crear cliente con email inválido: "email_invalido"
2. Intentar crear producto con precio negativo: -100

**Resultado Esperado**:
- Error de validación desde capa de negocio
- No se ejecuta query en BD

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

#### CP-SEC-004: Cierre de Sesión
**Objetivo**: Verificar que logout destruye sesión correctamente

**Pasos**:
1. Login exitoso
2. Click en "Cerrar Sesión"
3. Intentar acceder a dashboard con botón "Atrás"

**Resultado Esperado**:
- Sesión destruida
- Redirección a login
- No se puede acceder con botón atrás

**Resultado Real**: _______________
**Estado**: [ ] Aprobado [ ] Fallido

---

## 4. MATRIZ DE TRAZABILIDAD

| ID Caso | Módulo | Requisito | Prioridad | Estado |
|---------|--------|-----------|-----------|--------|
| CP-AUTH-001 | Autenticación | Login exitoso | Alta | |
| CP-AUTH-002 | Autenticación | Excepción usuario no existe | Alta | |
| CP-AUTH-003 | Autenticación | Excepción contraseña incorrecta | Alta | |
| CP-AUTH-004 | Autenticación | Incremento intentos | Alta | |
| CP-AUTH-005 | Autenticación | Bloqueo de cuenta | Alta | |
| CP-AUTH-006 | Autenticación | Excepción cuenta bloqueada | Alta | |
| CP-AUTH-007 | Autenticación | Desbloqueo de cuenta | Media | |
| CP-REG-001 | Registro | Registro exitoso | Alta | |
| CP-REG-002 | Registro | Validación email único | Media | |
| CP-CLI-001 | Clientes | Crear cliente | Alta | |
| CP-CLI-002 | Clientes | Editar cliente | Alta | |
| CP-CLI-003 | Clientes | Eliminar cliente | Alta | |
| CP-CON-001 | Contactos | Crear contacto | Alta | |
| CP-CON-002 | Contactos | Validación cliente obligatorio | Media | |
| CP-OPO-001 | Oportunidades | Crear oportunidad | Alta | |
| CP-ACT-001 | Actividades | Crear actividad | Alta | |
| CP-PRO-001 | Productos | Crear producto | Alta | |
| CP-DOC-001 | Documentos | Crear documento | Alta | |
| CP-DOC-002 | Documentos | Validar dropdown oportunidades | Alta | |
| CP-DASH-001 | Dashboard | Visualizar estadísticas | Alta | |
| CP-SEC-001 | Seguridad | Protección de rutas | Alta | |
| CP-SEC-002 | Seguridad | Prevención SQL Injection | Alta | |
| CP-SEC-003 | Seguridad | Validación en capa negocio | Alta | |
| CP-SEC-004 | Seguridad | Cierre de sesión | Alta | |

---

## 5. AMBIENTE DE PRUEBAS

### 5.1 Configuración del Entorno
\`\`\`
Sistema Operativo: Windows 10/11
Servidor Web: PHP Built-in Server (puerto 8000)
Base de Datos: MySQL 5.7+ (puerto 3306)
Navegadores: Chrome 120+, Firefox 120+, Edge 120+
\`\`\`

### 5.2 Datos de Prueba Base
\`\`\`sql
-- Usuario administrador
Email: admin@sistema.com
Password: admin123

-- Usuario vendedor
Email: vendedor@sistema.com
Password: vendedor123

-- Cliente de prueba
Nombre: Empresa Test S.A.
Email: test@empresa.com
\`\`\`

---

## 6. CRITERIOS DE ACEPTACIÓN

### 6.1 Criterios Generales
- ✓ 100% de casos de prueba de prioridad ALTA aprobados
- ✓ 80% de casos de prueba de prioridad MEDIA aprobados
- ✓ Todas las excepciones personalizadas funcionando correctamente
- ✓ Sin errores críticos de seguridad
- ✓ Arquitectura de tres capas respetada en todos los módulos

### 6.2 Criterios Específicos de Excepciones
- ✓ UsuarioNoExistenteException se lanza cuando email no existe
- ✓ ContraseñaIncorrectaException se lanza con contraseña incorrecta
- ✓ CuentaBloqueadaException se lanza después de 3 intentos fallidos
- ✓ Contador de intentos fallidos se incrementa correctamente
- ✓ Campo cuenta_bloqueada se actualiza en BD
- ✓ Mensajes de error claros y descriptivos

---

## 7. PROCEDIMIENTO DE EJECUCIÓN

### 7.1 Preparación
1. Importar script SQL actualizado (bd_clientes_v2_excepciones.sql)
2. Verificar que todos los datos de prueba están cargados
3. Iniciar servidor PHP: `php -S localhost:8000`
4. Iniciar MySQL en XAMPP
5. Abrir navegador con consola de desarrollador (F12)

### 7.2 Ejecución de Pruebas
1. Ejecutar casos en orden secuencial
2. Registrar resultados en columna "Resultado Real"
3. Marcar estado: Aprobado/Fallido
4. Capturar pantallas de casos fallidos
5. Verificar logs en BD después de cada prueba crítica

### 7.3 Verificación en Base de Datos
Después de cada prueba de autenticación, ejecutar:
\`\`\`sql
SELECT email, intentos_fallidos, cuenta_bloqueada, activo 
FROM Usuario 
WHERE email = 'admin@sistema.com';
\`\`\`

---

## 8. REGISTRO DE DEFECTOS

| ID | Fecha | Caso de Prueba | Descripción | Severidad | Estado |
|----|-------|----------------|-------------|-----------|--------|
| DEF-001 | | | | | |
| DEF-002 | | | | | |
| DEF-003 | | | | | |

**Niveles de Severidad**:
- **Crítico**: Sistema no funciona
- **Alto**: Funcionalidad principal afectada
- **Medio**: Funcionalidad secundaria afectada
- **Bajo**: Problema cosmético

---

## 9. INFORME FINAL

### 9.1 Resumen Ejecutivo
\`\`\`
Total de Casos de Prueba: 24
Casos Aprobados: ____
Casos Fallidos: ____
Porcentaje de Éxito: ____%

Defectos Encontrados: ____
Defectos Críticos: ____
Defectos Resueltos: ____
\`\`\`

### 9.2 Conclusiones
[Espacio para conclusiones después de ejecutar todas las pruebas]

### 9.3 Recomendaciones
[Espacio para recomendaciones de mejora]

---

## 10. APROBACIÓN

| Rol | Nombre | Firma | Fecha |
|-----|--------|-------|-------|
| Líder de Proyecto | | | |
| QA Tester | | | |
| Desarrollador | | | |

---

**Documento elaborado por**: [Nombre del Grupo]  
**Fecha de creación**: [Fecha]  
**Versión**: 1.0
