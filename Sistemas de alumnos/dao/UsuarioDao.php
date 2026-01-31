<?php
require_once __DIR__ . '/../modelo/Usuario.php';
require_once __DIR__ . '/../bd/conexion.php';

class UsuarioDao
{
    public function autenticar($usuario, $clave): ?Usuario
    {
        $conexion = new conexion();
        $pdo = $conexion->conectar();

        try {
            $sql = "SELECT * FROM usuarios WHERE usuario = :usuario AND clave = :clave";
            $stm = $pdo->prepare($sql);
            $stm->execute(['usuario' => $usuario, 'clave' => $clave]);
            $fila = $stm->fetch(PDO::FETCH_OBJ);

            if ($fila) {
                $usuario = new Usuario();
                $usuario->setId($fila->id);
                $usuario->setUsuario($fila->usuario);
                $usuario->setClave($fila->clave);
                 return $usuario;
            }

            return null;
        } catch (PDOException $e) {
            error_log("Error de login : " . $e->getMessage());
            return null;
        }
    }
}
?>