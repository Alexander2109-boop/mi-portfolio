CREATE DATABASE Personal;
USE Personal;

CREATE TABLE Alumnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(20) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20)NOT NULL,
    fechaNacimiento DATE
);
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL,
    clave VARCHAR(255) NOT NULL
);
INSERT INTO Usuarios (usuario, clave) 
VALUES ('admin', 'clave123');

INSERT INTO Alumno (cedula, nombre, apellido, correo, telefono, fechaNacimiento) VALUES
('1712345678', 'Carlos', 'Martínez', 'carlos.mtz@correo.com', '0991234567', '1998-05-15'),
('1723456789', 'Ana', 'García', 'ana.garcia@correo.com', '0992345678', '2000-11-20'),
('1734567890', 'Luis', 'Pérez', 'luis.perez@correo.com', '0993456789', '1999-02-10'),
('1745678901', 'María', 'Rodríguez', 'maria.rod@correo.com', '0994567890', '2001-08-25'),
('1756789012', 'Jorge', 'López', 'jorge.lopez@correo.com', '0995678901', '1997-12-05'),
('1767890123', 'Elena', 'Sánchez', 'elena.sanchez@correo.com', '0996789012', '2002-03-14'),
('1778901234', 'Ricardo', 'Torres', 'ricardo.t@correo.com', '0997890123', '1999-07-30'),
('1789012345', 'Lucía', 'Ramírez', 'lucia.ram@correo.com', '0998901234', '2000-01-22'),
('1790123456', 'Fernando', 'Castro', 'fer.castro@correo.com', '0999012345', '1998-10-10'),
('1701234567', 'Sofía', 'Herrera', 'sofia.h@correo.com', '0990123456', '2001-06-18');

