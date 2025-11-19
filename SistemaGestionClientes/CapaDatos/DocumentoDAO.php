<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../CapaEntidades/Documento.php';

/**
 * DAO para Documento
 */
class DocumentoDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    public function crear(Documento $documento) {
        $sql = "INSERT INTO Documento (oportunidad_id, nombre, url, tipo) 
                VALUES (:oportunidad_id, :nombre, :url, :tipo)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':oportunidad_id' => $documento->getOportunidadId(),
            ':nombre' => $documento->getNombre(),
            ':url' => $documento->getUrl(),
            ':tipo' => $documento->getTipo()
        ]);
    }

    public function listar() {
        $sql = "SELECT * FROM Documento ORDER BY fecha_subida DESC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Documento WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function actualizar(Documento $documento) {
        $sql = "UPDATE Documento SET oportunidad_id = :oportunidad_id, nombre = :nombre, 
                url = :url, tipo = :tipo WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':id' => $documento->getId(),
            ':oportunidad_id' => $documento->getOportunidadId(),
            ':nombre' => $documento->getNombre(),
            ':url' => $documento->getUrl(),
            ':tipo' => $documento->getTipo()
        ]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM Documento WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
