<?php
require_once __DIR__ . '/Conexion.php';                    // Importa la clase que gestiona la conexión a la base de datos (PDO)
require_once __DIR__ . '/../CapaEntidades/Documento.php';  // Importa la entidad Documento

/**
 * DAO para Documento
 * Basado en Procedimientos Almacenados de MySQL
 */
class DocumentoDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    /**
     * Registra un nuevo documento usando sp_documento_registrar
     */
    public function crear(Documento $documento) {
        $sql = "CALL sp_documento_registrar(?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        
        return $stmt->execute([
            $documento->getOportunidadId(),
            $documento->getNombre(),
            $documento->getUrl(),
            $documento->getTipo()
        ]);
    }

    /**
     * Lista todos los documentos usando sp_documento_listar
     */
    public function listar() {
        $sql = "CALL sp_documento_listar()";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un documento por ID usando sp_documento_obtener_uno
     */
    public function obtenerPorId($id) {
        $sql = "CALL sp_documento_obtener_uno(?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza un documento usando sp_documento_actualizar
     */
    public function actualizar(Documento $documento) {
        $sql = "CALL sp_documento_actualizar(?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        
        return $stmt->execute([
            $documento->getId(),
            $documento->getOportunidadId(),
            $documento->getNombre(),
            $documento->getUrl(),
            $documento->getTipo()
        ]);
    }

    /**
     * Elimina el registro usando sp_documento_eliminar
     */
    public function eliminar($id) {
        $sql = "CALL sp_documento_eliminar(?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }
}