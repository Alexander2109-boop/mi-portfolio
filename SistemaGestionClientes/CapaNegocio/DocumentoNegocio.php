<?php
require_once __DIR__ . '/../CapaDatos/DocumentoDAO.php';

/**
 * Capa de Negocio para Documento
 */
class DocumentoNegocio {
    private $documentoDAO;

    public function __construct() {
        $this->documentoDAO = new DocumentoDAO();
    }

    public function crear($oportunidad_id, $nombre, $url, $tipo) {
        if (empty($nombre) || empty($url)) {
            throw new Exception("Nombre y URL son requeridos");
        }

        $documento = new Documento(null, $oportunidad_id, $nombre, $url, $tipo, null);
        return $this->documentoDAO->crear($documento);
    }

    public function listar() {
        return $this->documentoDAO->listar();
    }

    public function obtenerPorId($id) {
        return $this->documentoDAO->obtenerPorId($id);
    }

    public function actualizar($id, $oportunidad_id, $nombre, $url, $tipo) {
        if (empty($nombre) || empty($url)) {
            throw new Exception("Nombre y URL son requeridos");
        }

        $documento = new Documento($id, $oportunidad_id, $nombre, $url, $tipo, null);
        return $this->documentoDAO->actualizar($documento);
    }

    public function eliminar($id) {
        return $this->documentoDAO->eliminar($id);
    }
}
