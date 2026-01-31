<?php
class conexion
{
    private $host = "localhost";
    private $usuario = "root";
    private $contrasena = "Paul2109.@";
    private $base_datos = "Personal;";
    private $port = "3306";

    public function conectar()
    {
        try {

            $conexion = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->base_datos", 
            $this->usuario, $this->contrasena);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            return null;
        }
    }
}
