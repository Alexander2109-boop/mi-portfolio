<?php
require_once __DIR__ . '/Conexion.php';                // Importa el archivo que gestiona la conexión PDO
require_once __DIR__ . '/../CapaEntidades/Cliente.php'; // Importa la entidad Cliente

/**
 * CAPA DE DATOS - ClienteDAO.php
 * Descripción: Data Access Object para la entidad Cliente
 * Propósito: Gestionar todas las operaciones CRUD en la tabla Cliente
 * Patrón de Diseño: DAO (Data Access Object) - Abstrae el acceso a datos
 * Principios SOLID:
 *   - Single Responsibility: Solo maneja operaciones de base de datos para Cliente
 *   - Open/Closed: Extensible para nuevas operaciones sin modificar las existentes
 */
class ClienteDAO {
    private $conexion;  // Instancia de la conexión PDO que permitirá ejecutar consultas SQL

    /**
     * Constructor - Inicializa la conexión a la base de datos
     */
    public function __construct() {
        $this->conexion = Conexion::getConexion(); // Obtiene la conexión (Singleton)
    }

    /**
     * Crear un nuevo cliente en la base de datos
     * 
     * @param Cliente $cliente - Objeto Cliente con los datos a insertar
     * @return bool True si se insertó correctamente, false en caso contrario
     */
    public function crear(Cliente $cliente) {
        // SQL con placeholders para prevenir inyección SQL
        $sql = "INSERT INTO Cliente (nombre, tipo_cliente_id, telefono, direccion, fecha_alta, activo) 
                VALUES (:nombre, :tipo_cliente_id, :telefono, :direccion, :fecha_alta, :activo)";
        
        $stmt = $this->conexion->prepare($sql); // Prepara la consulta SQL
        
        // Ejecuta la consulta sustituyendo los parámetros por los valores del objeto Cliente
        return $stmt->execute([
            ':nombre' => $cliente->getNombre(),
            ':tipo_cliente_id' => $cliente->getTipoClienteId(),
            ':telefono' => $cliente->getTelefono(),
            ':direccion' => $cliente->getDireccion(),
            ':fecha_alta' => $cliente->getFechaAlta(),
            ':activo' => $cliente->getActivo()
        ]);
    }

    /**
     * Listar todos los clientes con información del tipo de cliente
     * Usa INNER JOIN para obtener el nombre del tipo de cliente
     * 
     * @return array Array de clientes con sus datos completos
     */
    public function listar() {
        // Consulta con JOIN para traer también el nombre del tipo de cliente
        $sql = "SELECT c.*, tc.nombre as tipo_cliente_nombre 
                FROM Cliente c 
                INNER JOIN TipoCliente tc ON c.tipo_cliente_id = tc.id 
                ORDER BY c.nombre";
        
        $stmt = $this->conexion->query($sql); // Ejecuta consulta directa (sin parámetros)
        return $stmt->fetchAll(); // Retorna todos los registros como array asociativo
    }

    /**
     * Obtener un cliente específico por su ID
     * 
     * @param int $id - ID del cliente a buscar
     * @return array|false Array con datos del cliente o false si no existe
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Cliente WHERE id = :id"; // Consulta parametrizada
        $stmt = $this->conexion->prepare($sql);         // Prepara la consulta
        $stmt->execute([':id' => $id]);                 // Ejecuta con el ID enviado
        return $stmt->fetch();                          // Retorna un único registro o false
    }

    /**
     * Actualizar los datos de un cliente existente
     * 
     * @param Cliente $cliente - Objeto Cliente con los datos actualizados
     * @return bool True si se actualizó correctamente
     */
    public function actualizar(Cliente $cliente) {
        // SQL para actualizar todos los campos del cliente
        $sql = "UPDATE Cliente SET 
                nombre = :nombre, 
                tipo_cliente_id = :tipo_cliente_id, 
                telefono = :telefono, 
                direccion = :direccion, 
                fecha_alta = :fecha_alta, 
                activo = :activo 
                WHERE id = :id";
        
        $stmt = $this->conexion->prepare($sql); // Prepara la sentencia
        return $stmt->execute([
            ':id' => $cliente->getId(),              // ID del cliente que se actualizará
            ':nombre' => $cliente->getNombre(),
            ':tipo_cliente_id' => $cliente->getTipoClienteId(),
            ':telefono' => $cliente->getTelefono(),
            ':direccion' => $cliente->getDireccion(),
            ':fecha_alta' => $cliente->getFechaAlta(),
            ':activo' => $cliente->getActivo()
        ]); // Ejecuta con los nuevos valores
    }

    /**
     * Eliminar un cliente de la base de datos
     * Nota: Esto es una eliminación física. Para producción, considerar soft delete.
     * 
     * @param int $id - ID del cliente a eliminar
     * @return bool True si se eliminó correctamente
     */
    public function eliminar($id) {
        $sql = "DELETE FROM Cliente WHERE id = :id"; // Eliminación por ID
        $stmt = $this->conexion->prepare($sql);      // Prepara la consulta
        return $stmt->execute([':id' => $id]);       // Ejecuta con el ID
    }

    /**
     * Obtener todos los tipos de cliente disponibles
     * Usado para poblar dropdowns en formularios
     * 
     * @return array Array de tipos de cliente activos
     */
    public function obtenerTiposCliente() {
        // Solo trae tipos de cliente activos
        $sql = "SELECT * FROM TipoCliente WHERE activo = 1 ORDER BY nombre";
        return $this->conexion->query($sql)->fetchAll(); // Ejecuta y retorna todos
    }
}
