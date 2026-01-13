<?php
require_once __DIR__ . '/../CapaDatos/DocumentoDAO.php';
require_once __DIR__ . '/../CapaEntidades/Documento.php'; // Importación necesaria

/**
 * Capa de Negocio para Documento
 */
class DocumentoNegocio {
    private $documentoDAO;

    public function __construct() {
        $this->documentoDAO = new DocumentoDAO();
    }

    /**
     * Crear un nuevo documento
     */
    public function crear($oportunidad_id, $nombre, $url, $tipo) {
        // Validaciones de negocio
        if (empty($nombre) || empty($url)) {
            throw new Exception("El nombre y la URL del documento son obligatorios.");
        }

        // Crear la entidad Documento
        $documento = new Documento(
            null, 
            $oportunidad_id,
            $nombre,
            $url,
            $tipo,
            null // La fecha se asigna en la base de datos
        );

        // Delegar al DAO (que usa sp_documento_registrar)
        return $this->documentoDAO->crear($documento);
    }

    public function listar() {
        // Retorna el listado usando sp_documento_listar
        return $this->documentoDAO->listar();
    }

    public function obtenerPorId($id) {
        if (empty($id)) {
            throw new Exception("ID de documento no proporcionado.");
        }
        return $this->documentoDAO->obtenerPorId($id);
    }

    /**
     * Actualizar un documento existente
     */
    public function actualizar($id, $oportunidad_id, $nombre, $url, $tipo) {
        if (empty($id) || empty($nombre) || empty($url)) {
            throw new Exception("ID, Nombre y URL son requeridos para actualizar.");
        }

        $documento = new Documento(
            $id,
            $oportunidad_id,
            $nombre,
            $url,
            $tipo,
            null
        );

        // Delegar al DAO (que usa sp_documento_actualizar)
        return $this->documentoDAO->actualizar($documento);
    }

    /**
     * Eliminar un documento
     */
    public function eliminar($id) {
        if (empty($id)) {
            throw new Exception("ID inválido para eliminar.");
        }
        return $this->documentoDAO->eliminar($id);
    }
}