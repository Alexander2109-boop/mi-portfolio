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
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    /**
     * Guarda o actualiza un cliente usando el procedimiento almacenado
     */
    public function guardar(Cliente $cliente) {
        $sql = "CALL sp_cliente_guardar_completo(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        
        return $stmt->execute([
            $cliente->getId() ?? 0, // Si el ID es nulo, envía 0 para insertar
            $cliente->getNombre(),
            $cliente->getTipoClienteId(),
            $cliente->getTelefono(),
            $cliente->getDireccion(),
            $cliente->getFechaAlta(),
            $cliente->getActivo()
        ]);
    }

    /**
     * Lista todos los clientes usando el procedimiento y la vista
     */
    public function listar() {
        $sql = "CALL sp_cliente_listar_todo()";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un cliente por su ID
     */
    public function obtenerPorId($id) {
        $sql = "CALL sp_cliente_obtener_uno(?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Elimina un cliente por su ID
     */
    public function eliminar($id) {
        $sql = "CALL sp_cliente_borrar(?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Obtiene los tipos de cliente para los formularios
     */
    public function obtenerTiposCliente() {
        $sql = "CALL sp_cliente_obtener_tipos()";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}