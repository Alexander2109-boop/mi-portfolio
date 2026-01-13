<?php
require_once __DIR__ . '/Conexion.php';                 // Importa el archivo de conexión a la base de datos (PDO)
require_once __DIR__ . '/../CapaEntidades/Contacto.php'; // Importa la entidad Contacto

/**
 * DAO para Contacto
 * Responsable de realizar operaciones CRUD sobre la tabla Contacto
 */
class ContactoDAO {
    private $conexion; // Almacena la instancia PDO para ejecutar consultas SQL

    public function __construct() {
        // Obtiene la conexión mediante el método estático de la clase Conexion
        $this->conexion = Conexion::getConexion();
    }

    /**
     * Crear un nuevo contacto asociado a un cliente
     * 
     * @param Contacto $contacto Objeto con los datos a insertar
     * @return bool True si se insertó correctamente, false si falló
     */
    public function crear(Contacto $contacto) {
        // SQL con parámetros nombrados para evitar inyección SQL
        $sql = "INSERT INTO Contacto (cliente_id, nombre, cargo, email, telefono) 
                VALUES (:cliente_id, :nombre, :cargo, :email, :telefono)";
        
        // Prepara la consulta
        $stmt = $this->conexion->prepare($sql);
        
        // Ejecuta pasando los datos del objeto Contacto
        return $stmt->execute([
            ':cliente_id' => $contacto->getClienteId(),
            ':nombre' => $contacto->getNombre(),
            ':cargo' => $contacto->getCargo(),
            ':email' => $contacto->getEmail(),
            ':telefono' => $contacto->getTelefono()
        ]);
    }

    /**
     * Listar todos los contactos con datos del cliente relacionado
     * 
     * @return array Lista completa de contactos con información del cliente
     */
    public function listar() {
        // JOIN con Cliente para mostrar el nombre del cliente al que pertenece el contacto
        $sql = "SELECT con.*, c.nombre as cliente_nombre 
                FROM Contacto con 
                INNER JOIN Cliente c ON con.cliente_id = c.id 
                ORDER BY con.nombre";
        
        // Ejecuta la consulta de manera directa
        $stmt = $this->conexion->query($sql);
        
        // Retorna un array con todos los contactos
        return $stmt->fetchAll();
    }

    /**
     * Obtener un contacto específico por su ID
     * 
     * @param int $id ID del contacto a buscar
     * @return array|false Datos del contacto o false si no existe
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Contacto WHERE id = :id"; // Consulta para un contacto específico
        $stmt = $this->conexion->prepare($sql);          // Prepara la consulta
        $stmt->execute([':id' => $id]);                  // Ejecuta con el ID proporcionado
        return $stmt->fetch();                           // Retorna el registro o false
    }

    /**
     * Actualizar la información de un contacto existente
     * 
     * @param Contacto $contacto Objeto con los cambios a aplicar
     * @return bool True si se actualizó correctamente
     */
    public function actualizar(Contacto $contacto) {
        // SQL para actualizar todos los campos del contacto
        $sql = "UPDATE Contacto SET cliente_id = :cliente_id, nombre = :nombre, 
                cargo = :cargo, email = :email, telefono = :telefono WHERE id = :id";
        
        // Prepara la consulta
        $stmt = $this->conexion->prepare($sql);
        
        // Ejecuta con los nuevos valores provenientes del objeto Contacto
        return $stmt->execute([
            ':id' => $contacto->getId(),
            ':cliente_id' => $contacto->getClienteId(),
            ':nombre' => $contacto->getNombre(),
            ':cargo' => $contacto->getCargo(),
            ':email' => $contacto->getEmail(),
            ':telefono' => $contacto->getTelefono()
        ]);
    }

    /**
     * Eliminar un contacto de la base de datos
     * 
     * @param int $id ID del contacto a eliminar
     * @return bool True si la eliminación fue exitosa
     */
    public function eliminar($id) {
        $sql = "DELETE FROM Contacto WHERE id = :id"; // Eliminación física del registro
        $stmt = $this->conexion->prepare($sql);       // Prepara la sentencia
        return $stmt->execute([':id' => $id]);        // Ejecuta con el ID especificado
    }
}
