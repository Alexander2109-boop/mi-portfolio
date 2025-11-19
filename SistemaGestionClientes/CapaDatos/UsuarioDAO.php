<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../CapaEntidades/Usuario.php';

/**
 * DAO para Usuario - Acceso a datos
 */
class UsuarioDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    /**
     * Autenticar usuario - SIN ENCRIPTACIÓN
     */
    public function autenticar($email, $password) {
        $sql = "SELECT * FROM Usuario WHERE email = :email AND password = :password AND activo = 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':password' => $password
        ]);
        $row = $stmt->fetch();

        if ($row) {
            return new Usuario(
                $row['id'],
                $row['nombre'],
                $row['email'],
                $row['password'],
                $row['rol_id'],
                $row['activo']
            );
        }
        return null;
    }

    /**
     * Registrar nuevo usuario - SIN ENCRIPTACIÓN
     */
    public function registrar(Usuario $usuario) {
        $sql = "INSERT INTO Usuario (nombre, email, password, rol_id, activo) VALUES (:nombre, :email, :password, :rol_id, :activo)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':nombre' => $usuario->getNombre(),
            ':email' => $usuario->getEmail(),
            ':password' => $usuario->getPassword(),
            ':rol_id' => $usuario->getRolId(),
            ':activo' => $usuario->getActivo()
        ]);
    }

    /**
     * Listar todos los usuarios
     */
    public function listar() {
        $sql = "SELECT * FROM Usuario ORDER BY nombre";
        $stmt = $this->conexion->query($sql);
        $usuarios = [];
        while ($row = $stmt->fetch()) {
            $usuarios[] = new Usuario(
                $row['id'],
                $row['nombre'],
                $row['email'],
                $row['password'],
                $row['rol_id'],
                $row['activo']
            );
        }
        return $usuarios;
    }

    /**
     * Obtener usuario por ID
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Usuario WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        
        if ($row) {
            return new Usuario(
                $row['id'],
                $row['nombre'],
                $row['email'],
                $row['password'],
                $row['rol_id'],
                $row['activo']
            );
        }
        return null;
    }
}
