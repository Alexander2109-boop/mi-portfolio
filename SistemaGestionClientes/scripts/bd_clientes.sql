-- Base de datos para Sistema de Gestión de Clientes
-- Arquitectura: MySQL 8.0+

DROP DATABASE IF EXISTS bd_clientes;
CREATE DATABASE bd_clientes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bd_clientes;

-- Tabla de Roles
CREATE TABLE Rol (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

-- Tabla de Usuarios
CREATE TABLE Usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES Rol(id)
) ENGINE=InnoDB;

-- Tabla de Tipos de Cliente
CREATE TABLE TipoCliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

-- Tabla de Clientes
CREATE TABLE Cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    tipo_cliente_id INT NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    fecha_alta DATE,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (tipo_cliente_id) REFERENCES TipoCliente(id)
) ENGINE=InnoDB;

-- Tabla de Contactos
CREATE TABLE Contacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    cargo VARCHAR(100),
    email VARCHAR(100),
    telefono VARCHAR(20),
    FOREIGN KEY (cliente_id) REFERENCES Cliente(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla de Estados de Oportunidad
CREATE TABLE EstadoOportunidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    en_proceso BOOLEAN DEFAULT TRUE,
    ganada BOOLEAN DEFAULT FALSE,
    perdida BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB;

-- Tabla de Oportunidades
CREATE TABLE Oportunidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    estado_oportunidad_id INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    monto DECIMAL(15, 2) DEFAULT 0,
    descripcion TEXT,
    FOREIGN KEY (cliente_id) REFERENCES Cliente(id) ON DELETE CASCADE,
    FOREIGN KEY (estado_oportunidad_id) REFERENCES EstadoOportunidad(id)
) ENGINE=InnoDB;

-- Tabla de Tipos de Actividad
CREATE TABLE TipoActividad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

-- Tabla de Actividades
CREATE TABLE Actividad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    oportunidad_id INT NOT NULL,
    tipo_actividad_id INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    descripcion TEXT,
    FOREIGN KEY (oportunidad_id) REFERENCES Oportunidad(id) ON DELETE CASCADE,
    FOREIGN KEY (tipo_actividad_id) REFERENCES TipoActividad(id)
) ENGINE=InnoDB;

-- Tabla de Productos
CREATE TABLE Producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(15, 2) DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

-- Tabla de Documentos
CREATE TABLE Documento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    oportunidad_id INT NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    url TEXT NOT NULL,
    tipo VARCHAR(50),
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (oportunidad_id) REFERENCES Oportunidad(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Insertar datos iniciales
INSERT INTO Rol (nombre, activo) VALUES 
('ADMIN', TRUE),
('VENDEDOR', TRUE),
('SUPERVISOR', TRUE);

INSERT INTO TipoCliente (nombre, activo) VALUES 
('PERSONA', TRUE),
('EMPRESA', TRUE);

INSERT INTO EstadoOportunidad (nombre, en_proceso, ganada, perdida) VALUES 
('EN_PROCESO', TRUE, FALSE, FALSE),
('GANADA', FALSE, TRUE, FALSE),
('PERDIDA', FALSE, FALSE, TRUE);

INSERT INTO TipoActividad (nombre, activo) VALUES 
('LLAMADA', TRUE),
('EMAIL', TRUE),
('REUNION', TRUE),
('OTRO', TRUE);

-- Usuario por defecto: admin@sistema.com / admin123
INSERT INTO Usuario (nombre, email, password, rol_id, activo) VALUES 
('Administrador', 'admin@sistema.com', 'admin123', 1, TRUE);

-- Datos de prueba: Usuarios adicionales
INSERT INTO Usuario (nombre, email, password, rol_id, activo) VALUES 
('Juan Pérez', 'juan.perez@sistema.com', 'vendedor123', 2, TRUE),
('María González', 'maria.gonzalez@sistema.com', 'supervisor123', 3, TRUE),
('Carlos Ramírez', 'carlos.ramirez@sistema.com', 'vendedor456', 2, TRUE);

-- Datos de prueba: Clientes
INSERT INTO Cliente (nombre, tipo_cliente_id, telefono, direccion, fecha_alta, activo) VALUES 
('Tech Solutions S.A.', 2, '+52 55 1234 5678', 'Av. Reforma 123, CDMX', '2024-01-15', TRUE),
('Innovación Digital', 2, '+52 33 9876 5432', 'Calle Juárez 456, Guadalajara', '2024-02-20', TRUE),
('Roberto Martínez', 1, '+52 81 5555 1234', 'Monterrey Centro 789', '2024-03-10', TRUE),
('Corporativo Global', 2, '+52 55 8888 9999', 'Polanco 321, CDMX', '2024-01-25', TRUE),
('Laura Sánchez', 1, '+52 33 7777 4444', 'Zapopan 654, Jalisco', '2024-04-05', TRUE);

-- Datos de prueba: Contactos
INSERT INTO Contacto (cliente_id, nombre, cargo, email, telefono) VALUES 
(1, 'Ana López', 'Gerente de Compras', 'ana.lopez@techsolutions.com', '+52 55 1234 5679'),
(1, 'Pedro Ruiz', 'Director de TI', 'pedro.ruiz@techsolutions.com', '+52 55 1234 5680'),
(2, 'Sofía Fernández', 'CEO', 'sofia.fernandez@innovacion.com', '+52 33 9876 5433'),
(3, 'Roberto Martínez', 'Propietario', 'roberto.martinez@email.com', '+52 81 5555 1234'),
(4, 'Miguel Torres', 'Director General', 'miguel.torres@corporativo.com', '+52 55 8888 9990'),
(4, 'Carmen Díaz', 'Gerente de Proyectos', 'carmen.diaz@corporativo.com', '+52 55 8888 9991'),
(5, 'Laura Sánchez', 'Freelancer', 'laura.sanchez@email.com', '+52 33 7777 4444');

-- Datos de prueba: Oportunidades
INSERT INTO Oportunidad (cliente_id, estado_oportunidad_id, fecha_hora, monto, descripcion) VALUES 
(1, 1, '2024-05-15 10:00:00', 150000.00, 'Implementación de sistema ERP completo'),
(1, 2, '2024-03-20 14:30:00', 80000.00, 'Consultoría en transformación digital'),
(2, 1, '2024-06-01 09:00:00', 200000.00, 'Desarrollo de aplicación móvil personalizada'),
(3, 1, '2024-06-10 16:00:00', 45000.00, 'Sitio web corporativo con e-commerce'),
(4, 1, '2024-05-25 11:30:00', 300000.00, 'Infraestructura de servidores en la nube'),
(4, 3, '2024-04-10 13:00:00', 120000.00, 'Sistema de gestión documental'),
(5, 2, '2024-05-05 10:30:00', 35000.00, 'Diseño de identidad corporativa');

-- Datos de prueba: Actividades
INSERT INTO Actividad (oportunidad_id, tipo_actividad_id, fecha_hora, descripcion) VALUES 
(1, 3, '2024-05-16 10:00:00', 'Reunión inicial con el cliente para definir alcance del proyecto'),
(1, 1, '2024-05-20 15:30:00', 'Llamada de seguimiento para aclarar requerimientos técnicos'),
(1, 2, '2024-05-22 09:00:00', 'Envío de propuesta técnica y económica'),
(2, 1, '2024-03-21 11:00:00', 'Llamada de cierre - cliente acepta propuesta'),
(3, 3, '2024-06-02 14:00:00', 'Reunión de kickoff del proyecto'),
(3, 2, '2024-06-05 10:00:00', 'Envío de wireframes y mockups iniciales'),
(4, 1, '2024-06-11 09:30:00', 'Llamada para discutir funcionalidades del e-commerce'),
(5, 3, '2024-05-26 16:00:00', 'Presentación de propuesta de infraestructura'),
(5, 1, '2024-05-30 11:00:00', 'Negociación de precios y plazos'),
(7, 2, '2024-05-06 14:00:00', 'Envío de propuesta final de diseño - aprobada');

-- Datos de prueba: Productos
INSERT INTO Producto (nombre, descripcion, precio, activo) VALUES 
('Sistema ERP Empresarial', 'Sistema completo de planificación de recursos empresariales', 250000.00, TRUE),
('Aplicación Móvil iOS/Android', 'Desarrollo de app nativa para iOS y Android', 150000.00, TRUE),
('Sitio Web Corporativo', 'Diseño y desarrollo de sitio web responsive', 45000.00, TRUE),
('Consultoría de TI', 'Servicios de consultoría en tecnología - por hora', 2500.00, TRUE),
('Hosting Cloud Premium', 'Servicio de hosting en la nube con 99.9% uptime', 8000.00, TRUE),
('Sistema CRM', 'Gestión de relaciones con clientes', 120000.00, TRUE),
('Diseño de Marca', 'Identidad corporativa completa', 35000.00, TRUE),
('Mantenimiento Web', 'Mantenimiento mensual de sitio web', 5000.00, TRUE),
('Campaña de Marketing Digital', 'Estrategia completa de marketing online', 60000.00, TRUE),
('Capacitación en Software', 'Entrenamiento de usuario final - por día', 8500.00, TRUE);

-- Datos de prueba: Documentos
INSERT INTO Documento (oportunidad_id, nombre, url, tipo, fecha_subida) VALUES 
(1, 'Propuesta Técnica ERP', 'https://docs.ejemplo.com/propuesta-erp-techsolutions.pdf', 'PDF', '2024-05-22 10:30:00'),
(1, 'Contrato de Servicios', 'https://docs.ejemplo.com/contrato-erp-001.pdf', 'PDF', '2024-05-25 14:00:00'),
(2, 'Informe de Consultoría', 'https://docs.ejemplo.com/informe-consultoria.docx', 'Word', '2024-03-22 09:15:00'),
(3, 'Especificaciones App Móvil', 'https://docs.ejemplo.com/specs-app-movil.xlsx', 'Excel', '2024-06-03 11:00:00'),
(3, 'Wireframes UI/UX', 'https://docs.ejemplo.com/wireframes-app.pdf', 'PDF', '2024-06-05 16:30:00'),
(4, 'Propuesta Web E-commerce', 'https://docs.ejemplo.com/propuesta-ecommerce.pdf', 'PDF', '2024-06-11 10:00:00'),
(5, 'Arquitectura Cloud', 'https://docs.ejemplo.com/arquitectura-cloud.pdf', 'PDF', '2024-05-26 17:00:00'),
(5, 'Cotización de Servidores', 'https://docs.ejemplo.com/cotizacion-servidores.xlsx', 'Excel', '2024-05-28 09:00:00'),
(7, 'Manual de Marca', 'https://docs.ejemplo.com/manual-marca-final.pdf', 'PDF', '2024-05-06 15:00:00');
