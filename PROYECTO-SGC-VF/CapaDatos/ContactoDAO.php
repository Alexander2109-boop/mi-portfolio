<?php
require_once __DIR__ . '/Conexion.php';                 // Importa el archivo de conexión a la base de datos (PDO)
require_once __DIR__ . '/../CapaEntidades/Contacto.php'; // Importa la entidad Contacto

/**
 * DAO para Contacto
 * Responsable de realizar operaciones CRUD sobre la tabla Contacto
 */
class ContactoDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    /**
     * Guarda o actualiza un contacto usando sp_contacto_upsert
     */
    public function guardar(Contacto $contacto) {
        // El procedimiento espera: p_id, p_cli, p_nom, p_ema, p_tel, p_car
        $sql = "CALL sp_contacto_upsert(?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        
        return $stmt->execute([
            $contacto->getId() ?? 0, // Si es nuevo, envía 0
            $contacto->getClienteId(),
            $contacto->getNombre(),
            $contacto->getEmail(),
            $contacto->getTelefono(),
            $contacto->getCargo()
        ]);
    }

    /**
     * Lista todos los contactos usando la vista y el procedimiento
     */
    public function listar() {
        $sql = "CALL sp_contacto_listar()";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un contacto por su ID
     */
    public function obtenerPorId($id) {
        $sql = "CALL sp_contacto_obtener_uno(?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Elimina un contacto usando sp_contacto_eliminar
     */
    public function eliminar($id) {
        $sql = "CALL sp_contacto_eliminar(?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }
}