-- 1. CONFIGURACIÓN INICIAL
DROP DATABASE IF EXISTS bd_clientes;
CREATE DATABASE bd_clientes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bd_clientes;

-- 2. TABLAS DEL SISTEMA
CREATE TABLE Rol (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE Usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    intentos_fallidos INT DEFAULT 0,
    bloqueado BOOLEAN DEFAULT FALSE,
    fecha_bloqueo TIMESTAMP NULL,
    CONSTRAINT fk_usuario_rol FOREIGN KEY (rol_id) REFERENCES Rol(id) ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE TipoCliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE Cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    tipo_cliente_id INT NOT NULL,
    telefono VARCHAR(10),
    direccion TEXT,
    activo BOOLEAN DEFAULT TRUE,
    fecha_alta DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_cliente_tipo FOREIGN KEY (tipo_cliente_id) REFERENCES TipoCliente(id) ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Contacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    cargo VARCHAR(100),
    email VARCHAR(100),
    telefono VARCHAR(20),
    CONSTRAINT fk_contacto_cliente FOREIGN KEY (cliente_id) REFERENCES Cliente(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE EstadoOportunidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE Oportunidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    estado_oportunidad_id INT NOT NULL,
    monto DECIMAL(15, 2) DEFAULT 0,
    descripcion TEXT,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_opt_cliente FOREIGN KEY (cliente_id) REFERENCES Cliente(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_opt_estado FOREIGN KEY (estado_oportunidad_id) REFERENCES EstadoOportunidad(id) ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(15, 2) DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

CREATE TABLE Documento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    oportunidad_id INT NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    url TEXT NOT NULL,
    tipo VARCHAR(50),
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_doc_opt FOREIGN KEY (oportunidad_id) REFERENCES Oportunidad(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE TipoActividad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE Actividad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    oportunidad_id INT NOT NULL,
    tipo_actividad_id INT NOT NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    descripcion TEXT,
    CONSTRAINT fk_act_opt FOREIGN KEY (oportunidad_id) REFERENCES Oportunidad(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_act_tipo FOREIGN KEY (tipo_actividad_id) REFERENCES TipoActividad(id) ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 3. VISTAS
CREATE VIEW vista_usuarios_detallados AS SELECT u.*, r.nombre AS rol_nombre FROM Usuario u JOIN Rol r ON u.rol_id = r.id;
CREATE VIEW vista_clientes_detallados AS SELECT c.*, tc.nombre AS tipo_nombre FROM Cliente c JOIN TipoCliente tc ON c.tipo_cliente_id = tc.id;
CREATE VIEW vista_oportunidades_detalladas AS SELECT o.*, c.nombre AS cliente_nombre, eo.nombre AS estado_nombre FROM Oportunidad o JOIN Cliente c ON o.cliente_id = c.id JOIN EstadoOportunidad eo ON o.estado_oportunidad_id = eo.id;
CREATE VIEW vista_actividades_detalladas AS SELECT a.*, ta.nombre AS tipo_actividad FROM Actividad a JOIN TipoActividad ta ON a.tipo_actividad_id = ta.id;
CREATE VIEW vista_contactos_detallados AS SELECT con.*, c.nombre as cliente_nombre FROM Contacto con INNER JOIN Cliente c ON con.cliente_id = c.id;

-- 4. PROCEDIMIENTOS ALMACENADOS
DELIMITER //

-- USUARIOS Y SEGURIDAD
CREATE PROCEDURE sp_usuario_autenticar(IN p_email VARCHAR(100), IN p_password VARCHAR(255))
BEGIN
    DECLARE v_id INT;
    DECLARE v_pass_db VARCHAR(255);
    DECLARE v_bloqueado BOOLEAN;
    DECLARE v_intentos INT;
    SELECT id, password, bloqueado, intentos_fallidos INTO v_id, v_pass_db, v_bloqueado, v_intentos FROM Usuario WHERE email = p_email;
    IF v_id IS NULL THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Usuario no encontrado';
    ELSEIF v_bloqueado = TRUE THEN SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = 'Cuenta bloqueada';
    ELSEIF v_pass_db <> p_password THEN
        UPDATE Usuario SET intentos_fallidos = intentos_fallidos + 1 WHERE id = v_id;
        IF (v_intentos + 1) >= 3 THEN UPDATE Usuario SET bloqueado = TRUE, fecha_bloqueo = NOW() WHERE id = v_id; END IF;
        SIGNAL SQLSTATE '45002' SET MESSAGE_TEXT = 'Clave incorrecta';
    ELSE
        UPDATE Usuario SET intentos_fallidos = 0, fecha_bloqueo = NULL WHERE id = v_id;
        SELECT * FROM Usuario WHERE id = v_id;
    END IF;
END //

CREATE PROCEDURE sp_usuario_registrar(IN p_nom VARCHAR(100), IN p_email VARCHAR(100), IN p_pass VARCHAR(255), IN p_rol INT)
BEGIN INSERT INTO Usuario (nombre, email, password, rol_id) VALUES (p_nom, p_email, p_pass, p_rol); END //

CREATE PROCEDURE sp_usuario_listar() BEGIN SELECT * FROM vista_usuarios_detallados ORDER BY nombre; END //

CREATE PROCEDURE sp_usuario_obtener_uno(IN p_id INT) BEGIN SELECT * FROM Usuario WHERE id = p_id; END //

CREATE PROCEDURE sp_usuario_desbloquear(IN p_id INT)
BEGIN UPDATE Usuario SET bloqueado = FALSE, intentos_fallidos = 0, fecha_bloqueo = NULL WHERE id = p_id; END //

-- PRODUCTOS
CREATE PROCEDURE sp_producto_upsert(IN p_id INT, IN p_nom VARCHAR(200), IN p_desc TEXT, IN p_pre DECIMAL(15,2), IN p_act BOOLEAN)
BEGIN
    IF p_id = 0 THEN INSERT INTO Producto (nombre, descripcion, precio, activo) VALUES (p_nom, p_desc, p_pre, p_act);
    ELSE UPDATE Producto SET nombre = p_nom, descripcion = p_desc, precio = p_pre, activo = p_act WHERE id = p_id; END IF;
END //

CREATE PROCEDURE sp_producto_listar() BEGIN SELECT * FROM Producto ORDER BY nombre; END //

CREATE PROCEDURE sp_producto_obtener_uno(IN p_id INT) BEGIN SELECT * FROM Producto WHERE id = p_id; END //

CREATE PROCEDURE sp_producto_eliminar(IN p_id INT) BEGIN DELETE FROM Producto WHERE id = p_id; END //

-- CLIENTES
CREATE PROCEDURE sp_cliente_guardar_completo(IN p_id INT, IN p_nom VARCHAR(200), IN p_tipo INT, IN p_tel VARCHAR(10), IN p_dir TEXT, IN p_fecha DATETIME, IN p_act BOOLEAN)
BEGIN
    IF p_id = 0 THEN INSERT INTO Cliente (nombre, tipo_cliente_id, telefono, direccion, fecha_alta, activo) VALUES (p_nom, p_tipo, p_tel, p_dir, p_fecha, p_act);
    ELSE UPDATE Cliente SET nombre = p_nom, tipo_cliente_id = p_tipo, telefono = p_tel, direccion = p_dir, fecha_alta = p_fecha, activo = p_act WHERE id = p_id; END IF;
END //

CREATE PROCEDURE sp_cliente_listar_todo() BEGIN SELECT * FROM vista_clientes_detallados ORDER BY nombre; END //

CREATE PROCEDURE sp_cliente_obtener_uno(IN p_id INT) BEGIN SELECT * FROM Cliente WHERE id = p_id; END //

CREATE PROCEDURE sp_cliente_borrar(IN p_id INT) BEGIN DELETE FROM Cliente WHERE id = p_id; END //

CREATE PROCEDURE sp_cliente_obtener_tipos() BEGIN SELECT * FROM TipoCliente ORDER BY nombre; END //

-- OPORTUNIDADES
CREATE PROCEDURE sp_oportunidad_registrar_completo(IN p_cli INT, IN p_est INT, IN p_mon DECIMAL(15,2), IN p_desc TEXT, IN p_fecha DATETIME)
BEGIN INSERT INTO Oportunidad (cliente_id, estado_oportunidad_id, monto, descripcion, fecha_hora) VALUES (p_cli, p_est, p_mon, p_desc, p_fecha); END //

CREATE PROCEDURE sp_oportunidad_actualizar_completo(IN p_id INT, IN p_cli INT, IN p_est INT, IN p_mon DECIMAL(15,2), IN p_desc TEXT, IN p_fecha DATETIME)
BEGIN UPDATE Oportunidad SET cliente_id = p_cli, estado_oportunidad_id = p_est, monto = p_mon, descripcion = p_desc, fecha_hora = p_fecha WHERE id = p_id; END //

CREATE PROCEDURE sp_oportunidad_listar() BEGIN SELECT * FROM vista_oportunidades_detalladas; END //

CREATE PROCEDURE sp_oportunidad_obtener_uno(IN p_id INT) BEGIN SELECT * FROM Oportunidad WHERE id = p_id; END //

CREATE PROCEDURE sp_oportunidad_eliminar(IN p_id INT) BEGIN DELETE FROM Oportunidad WHERE id = p_id; END //

CREATE PROCEDURE sp_oportunidad_estados() BEGIN SELECT * FROM EstadoOportunidad; END //

-- CONTACTOS
CREATE PROCEDURE sp_contacto_upsert(IN p_id INT, IN p_cli INT, IN p_nom VARCHAR(100), IN p_ema VARCHAR(100), IN p_tel VARCHAR(20), IN p_car VARCHAR(100))
BEGIN
    IF p_id = 0 THEN INSERT INTO Contacto (cliente_id, nombre, email, telefono, cargo) VALUES (p_cli, p_nom, p_ema, p_tel, p_car);
    ELSE UPDATE Contacto SET nombre = p_nom, email = p_ema, telefono = p_tel, cargo = p_car WHERE id = p_id; END IF;
END //

CREATE PROCEDURE sp_contacto_listar() BEGIN SELECT * FROM vista_contactos_detallados ORDER BY nombre; END //

CREATE PROCEDURE sp_contacto_obtener_uno(IN p_id INT) BEGIN SELECT * FROM Contacto WHERE id = p_id; END //

CREATE PROCEDURE sp_contacto_eliminar(IN p_id INT) BEGIN DELETE FROM Contacto WHERE id = p_id; END //

-- DOCUMENTOS
CREATE PROCEDURE sp_documento_registrar(IN p_opt INT, IN p_nom VARCHAR(200), IN p_url TEXT, IN p_tip VARCHAR(50))
BEGIN INSERT INTO Documento (oportunidad_id, nombre, url, tipo) VALUES (p_opt, p_nom, p_url, p_tip); END //

CREATE PROCEDURE sp_documento_actualizar(IN p_id INT, IN p_opt INT, IN p_nom VARCHAR(200), IN p_url TEXT, IN p_tip VARCHAR(50))
BEGIN UPDATE Documento SET oportunidad_id = p_opt, nombre = p_nom, url = p_url, tipo = p_tip WHERE id = p_id; END //

CREATE PROCEDURE sp_documento_listar()
BEGIN SELECT d.*, o.descripcion as oportunidad_nombre FROM Documento d INNER JOIN Oportunidad o ON d.oportunidad_id = o.id ORDER BY d.fecha_subida DESC; END //

CREATE PROCEDURE sp_documento_obtener_uno(IN p_id INT) BEGIN SELECT * FROM Documento WHERE id = p_id; END //

CREATE PROCEDURE sp_documento_eliminar(IN p_id INT) BEGIN DELETE FROM Documento WHERE id = p_id; END //

-- ACTIVIDADES
CREATE PROCEDURE sp_actividad_registrar(IN p_opt INT, IN p_tip INT, IN p_desc TEXT)
BEGIN INSERT INTO Actividad (oportunidad_id, tipo_actividad_id, descripcion) VALUES (p_opt, p_tip, p_desc); END //

CREATE PROCEDURE sp_actividad_actualizar(IN p_id INT, IN p_desc TEXT)
BEGIN UPDATE Actividad SET descripcion = p_desc WHERE id = p_id; END //

CREATE PROCEDURE sp_actividad_listar() BEGIN SELECT * FROM vista_actividades_detalladas; END //

CREATE PROCEDURE sp_actividad_obtener(IN p_id INT) BEGIN SELECT * FROM Actividad WHERE id = p_id; END //

CREATE PROCEDURE sp_actividad_eliminar(IN p_id INT) BEGIN DELETE FROM Actividad WHERE id = p_id; END //

CREATE PROCEDURE sp_actividad_tipos() BEGIN SELECT * FROM TipoActividad ORDER BY nombre; END //

DELIMITER ;

-- 5. DATOS BASE
USE bd_clientes;

USE bd_clientes;

-- 1. Roles
INSERT INTO Rol (nombre) VALUES 
('ADMIN'),
('VENDEDOR'),
('SUPERVISOR');

-- 2. Tipos de Cliente
INSERT INTO TipoCliente (nombre) VALUES 
('PERSONA'),
('EMPRESA');

-- 3. Estados de Oportunidad
INSERT INTO EstadoOportunidad (nombre) VALUES 
('EN_PROCESO'),
('GANADA'),
('PERDIDA');

-- 4. Tipos de Actividad
INSERT INTO TipoActividad (nombre) VALUES 
('LLAMADA'),
('EMAIL'),
('REUNION'),
('OTRO');

-- 5. Usuarios
INSERT INTO Usuario (nombre, email, password, rol_id, activo) VALUES 
('Administrador', 'admin@sistema.com', 'admin123', 1, TRUE),
('Juan Pérez', 'juan.perez@sistema.com', 'vendedor123', 2, TRUE),
('María González', 'maria.gonzalez@sistema.com', 'supervisor123', 3, TRUE),
('Carlos Ramírez', 'carlos.ramirez@sistema.com', 'vendedor456', 2, TRUE);

-- 6. Clientes (Teléfonos corregidos a 10 dígitos)
INSERT INTO Cliente (nombre, tipo_cliente_id, telefono, direccion, fecha_alta, activo) VALUES 
('Tech Solutions S.A.', 2, '5512345678', 'Av. Reforma 123, CDMX', '2024-01-15', TRUE),
('Innovación Digital', 2, '3398765432', 'Calle Juárez 456, Guadalajara', '2024-02-20', TRUE),
('Roberto Martínez', 1, '8155551234', 'Monterrey Centro 789', '2024-03-10', TRUE),
('Corporativo Global', 2, '5588889999', 'Polanco 321, CDMX', '2024-01-25', TRUE),
('Laura Sánchez', 1, '3377774444', 'Zapopan 654, Jalisco', '2024-04-05', TRUE);

-- 7. Contactos (Teléfonos corregidos a 10 dígitos)
INSERT INTO Contacto (cliente_id, nombre, cargo, email, telefono) VALUES 
(1, 'Ana López', 'Gerente de Compras', 'ana.lopez@techsolutions.com', '5512345679'),
(1, 'Pedro Ruiz', 'Director de TI', 'pedro.ruiz@techsolutions.com', '5512345680'),
(2, 'Sofía Fernández', 'CEO', 'sofia.fernandez@innovacion.com', '3398765433'),
(3, 'Roberto Martínez', 'Propietario', 'roberto.martinez@email.com', '8155551234'),
(4, 'Miguel Torres', 'Director General', 'miguel.torres@corporativo.com', '5588889990'),
(4, 'Carmen Díaz', 'Gerente de Proyectos', 'carmen.diaz@corporativo.com', '5588889991'),
(5, 'Laura Sánchez', 'Freelancer', 'laura.sanchez@email.com', '3377774444');

-- 8. Oportunidades
INSERT INTO Oportunidad (cliente_id, estado_oportunidad_id, fecha_hora, monto, descripcion) VALUES 
(1, 1, '2024-05-15 10:00:00', 150000.00, 'Implementación de sistema ERP completo'),
(1, 1, '2024-03-20 14:30:00', 80000.00, 'Consultoría en transformación digital'),
(2, 1, '2024-06-01 09:00:00', 200000.00, 'Desarrollo de aplicación móvil personalizada'),
(3, 1, '2024-06-10 16:00:00', 45000.00, 'Sitio web corporativo con e-commerce'),
(4, 1, '2024-05-25 11:30:00', 300000.00, 'Infraestructura de servidores en la nube'),
(4, 2, '2024-04-10 13:00:00', 120000.00, 'Sistema de gestión documental'),
(5, 1, '2024-05-05 10:30:00', 35000.00, 'Diseño de identidad corporativa');

-- 9. Actividades
INSERT INTO Actividad (oportunidad_id, tipo_actividad_id, fecha_hora, descripcion) VALUES 
(1, 3, '2024-05-16 10:00:00', 'Reunión inicial para definir alcance'),
(1, 1, '2024-05-20 15:30:00', 'Llamada para requerimientos técnicos'),
(1, 2, '2024-05-22 09:00:00', 'Envío de propuesta técnica'),
(2, 1, '2024-03-21 11:00:00', 'Llamada de cierre - aceptada'),
(3, 3, '2024-06-02 14:00:00', 'Reunión de kickoff del proyecto'),
(4, 1, '2024-06-11 09:30:00', 'Llamada funcionalidades e-commerce'),
(5, 3, '2024-05-26 16:00:00', 'Presentación de propuesta'),
(5, 1, '2024-05-30 11:00:00', 'Negociación de precios');

-- 10. Productos
INSERT INTO Producto (nombre, descripcion, precio, activo) VALUES 
('Sistema ERP Empresarial', 'Sistema completo de planificación', 250000.00, TRUE),
('Aplicación Móvil iOS/Android', 'Desarrollo de app nativa', 150000.00, TRUE),
('Sitio Web Corporativo', 'Diseño y desarrollo responsive', 45000.00, TRUE),
('Consultoría de TI', 'Servicios de consultoría por hora', 2500.00, TRUE),
('Hosting Cloud Premium', 'Servicio de hosting 99.9% uptime', 8000.00, TRUE),
('Sistema CRM', 'Gestión de relaciones con clientes', 120000.00, TRUE),
('Diseño de Marca', 'Identidad corporativa completa', 35000.00, TRUE),
('Mantenimiento Web', 'Mantenimiento mensual', 5000.00, TRUE),
('Campaña Marketing Digital', 'Estrategia de marketing online', 60000.00, TRUE),
('Capacitación Software', 'Entrenamiento usuario final', 8500.00, TRUE);

-- 11. Documentos
INSERT INTO Documento (oportunidad_id, nombre, url, tipo, fecha_subida) VALUES 
(1, 'Propuesta Técnica ERP', 'http://docs.com/propuesta-erp.pdf', 'PDF', '2024-05-22 10:30:00'),
(1, 'Contrato de Servicios', 'http://docs.com/contrato-erp.pdf', 'PDF', '2024-05-25 14:00:00'),
(2, 'Informe Consultoría', 'http://docs.com/informe.docx', 'Word', '2024-03-22 09:15:00'),
(3, 'Especificaciones App', 'http://docs.com/specs.xlsx', 'Excel', '2024-06-03 11:00:00'),
(3, 'Wireframes UI/UX', 'http://docs.com/wireframes.pdf', 'PDF', '2024-06-05 16:30:00'),
(4, 'Propuesta Ecommerce', 'http://docs.com/propuesta-web.pdf', 'PDF', '2024-06-11 10:00:00'),
(5, 'Arquitectura Cloud', 'http://docs.com/arquitectura.pdf', 'PDF', '2024-05-26 17:00:00'),
(5, 'Cotización Servidores', 'http://docs.com/cotizacion.xlsx', 'Excel', '2024-05-28 09:00:00');
