<?php
/**
 * CAPA DE DATOS - Conexion.php
 * Descripción: Clase para gestionar la conexión a la base de datos MySQL
 * Patrón de Diseño: Singleton - Asegura una única instancia de conexión
 * Tecnología: PDO (PHP Data Objects) para seguridad y portabilidad
 */
class Conexion {
    // Configuración de la base de datos
    private static $host = 'localhost';       // Servidor de base de datos
    private static $dbname = 'bd_clientes';   // Nombre de la base de datos
    private static $username = 'root';        // Usuario de MySQL
    private static $password = '';            // Contraseña de MySQL (vacía en XAMPP)
    
    // Variable estática que almacena la única instancia (Singleton)
    private static $conexion = null;

    /**
     * Método estático para obtener la conexión PDO
     * Implementa el patrón Singleton: solo se crea una conexión para toda la aplicación
     * 
     * @return PDO Objeto de conexión PDO configurado
     * @throws PDOException Si falla la conexión a la base de datos
     */
    public static function getConexion() {
        // Verificar si ya existe una conexión
        if (self::$conexion === null) {
            try {
                // Crear nueva conexión PDO con configuración de seguridad
                self::$conexion = new PDO(
                    // DSN (Data Source Name) - string de conexión
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8mb4",
                    self::$username,
                    self::$password,
                    [
                        // Configuración de PDO para máxima seguridad y rendimiento
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,           // Lanzar excepciones en errores
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      // Retornar arrays asociativos
                        PDO::ATTR_EMULATE_PREPARES => false                     // Usar prepared statements reales
                    ]
                );
            } catch (PDOException $e) {
                // Si falla la conexión, terminar la ejecución y mostrar el error
                die("Error de conexión a la base de datos: " . $e->getMessage());
            }
        }
        // Retornar la conexión existente o recién creada
        return self::$conexion;
    }
}
