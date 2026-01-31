<?php
require_once __DIR__ . '/../modelo/Alumno.php';
require_once __DIR__ . '/../bd/conexion.php';

class AlumnoDao
{
    private $pdo;

    public function __construct()
    {
        $con = new conexion();
        $this->pdo = $con->conectar();
    }
    // obtener todos
    public function obtenerTodos()
    {
        // Usamos "AS" para renombrar las columnas y que coincidan con tu Vista
        $sql = "SELECT id AS Id, 
                   cedula AS Cedula, 
                   nombre AS Nombres, 
                   apellido AS Apellidos, 
                   correo AS Correo, 
                   telefono AS Telefono, 
                   fechaNacimiento AS FechaNacimiento 
            FROM Alumnos 
            ORDER BY id ASC";

        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // buscar por nombre
    // buscar por nombre
    public function buscarPorNombre($valor)
    {
        // Agregamos los alias (AS) para que coincidan con la Vista
        $sql = "SELECT id AS Id, cedula AS Cedula, nombre AS Nombres, apellido AS Apellidos, 
                       correo AS Correo, telefono AS Telefono, fechaNacimiento AS FechaNacimiento 
                FROM Alumnos WHERE nombre LIKE :valor ORDER BY nombre ASC";

        $stm = $this->pdo->prepare($sql);
        $stm->execute(['valor' => '%' . $valor . '%']);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // buscar por apellido
    public function buscarPorApellido($valor)
    {
        // Agregamos los alias (AS) para que coincidan con la Vista
        $sql = "SELECT id AS Id, cedula AS Cedula, nombre AS Nombres, apellido AS Apellidos, 
                       correo AS Correo, telefono AS Telefono, fechaNacimiento AS FechaNacimiento 
                FROM Alumnos WHERE apellido LIKE :valor ORDER BY apellido ASC";

        $stm = $this->pdo->prepare($sql);
        $stm->execute(['valor' => '%' . $valor . '%']);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    // guardar

    public function guardar($datos)
    {
        try {
            $sql = "INSERT INTO Alumnos (cedula, nombre, apellido, correo, telefono, fechaNacimiento) 
                    VALUES (:cedula, :nombre, :apellido, :correo, :telefono, :fechaNacimiento)";
            $stm = $this->pdo->prepare($sql);
            return $stm->execute([
                'cedula' => $datos['cedula'],
                'nombre' => $datos['nombre'],
                'apellido' => $datos['apellido'],
                'correo' => $datos['correo'],
                'telefono' => $datos['telefono'],
                'fechaNacimiento' => $datos['fechaNacimiento']
            ]);
        } catch (PDOException $e) {
            error_log("Error al guardar alumno: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT id AS Id, cedula AS Cedula, nombre AS Nombres, apellido AS Apellidos, 
                   correo AS Correo, telefono AS Telefono, fechaNacimiento AS FechaNacimiento 
            FROM Alumnos WHERE id = :id";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(['id' => $id]);
        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM Alumnos WHERE id = :id";
        $stm = $this->pdo->prepare($sql);
        return $stm->execute(['id' => $id]);
    }
}
