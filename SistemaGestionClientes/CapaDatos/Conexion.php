<?php
/**
 * Clase Conexion - Maneja la conexión a la base de datos usando PDO
 * Patrón Singleton para una única instancia de conexión
 */
class Conexion {
    private static $host = 'localhost';
    private static $dbname = 'bd_clientes';
    private static $username = 'root';
    private static $password = '';
    private static $conexion = null;

    /**
     * Obtiene la conexión PDO a la base de datos
     * @return PDO Conexión activa
     */
    public static function getConexion() {
        if (self::$conexion === null) {
            try {
                self::$conexion = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8mb4",
                    self::$username,
                    self::$password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$conexion;
    }
}
