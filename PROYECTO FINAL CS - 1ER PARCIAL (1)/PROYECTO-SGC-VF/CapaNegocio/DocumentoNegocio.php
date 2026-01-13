<?php
require_once __DIR__ . '/../CapaDatos/DocumentoDAO.php';

/**
 * Capa de Negocio para Documento
 *
 * Esta clase se encarga de aplicar validaciones y reglas de negocio
 * antes de delegar las operaciones a la capa de datos (DocumentoDAO).
 */
class DocumentoNegocio {
    private $documentoDAO;

    /**
     * Constructor:
     * Inicializa el DAO de Documento para manejar operaciones en BD.
     */
    public function __construct() {
        $this->documentoDAO = new DocumentoDAO();
    }

    /**
     * Crear un nuevo documento asociado a una oportunidad.
     *
     * @param int    $oportunidad_id ID de la oportunidad asociada
     * @param string $nombre         Nombre del documento (requerido)
     * @param string $url            URL o ruta del archivo (requerido)
     * @param string $tipo           Tipo o categoría del documento
     *
     * @throws Exception Si el nombre o la URL están vacíos
     */
    public function crear($oportunidad_id, $nombre, $url, $tipo) {
        // Validaciones mínimas obligatorias
        if (empty($nombre) || empty($url)) {
            throw new Exception("Nombre y URL son requeridos");
        }

        // Crear la entidad Documento
        $documento = new Documento(
            null,               // ID nulo porque será autogenerado
            $oportunidad_id,
            $nombre,
            $url,
            $tipo,
            null                // Fecha de subida (se puede asignar en el DAO)
        );

        // Delegar la inserción al DAO
        return $this->documentoDAO->crear($documento);
    }

    /**
     * Listar todos los documentos registrados.
     *
     * @return array Arreglo de objetos Documento
     */
    public function listar() {
        return $this->documentoDAO->listar();
    }

    /**
     * Obtener un documento específico mediante su ID.
     *
     * @param int $id ID del documento a buscar
     */
    public function obtenerPorId($id) {
        return $this->documentoDAO->obtenerPorId($id);
    }

    /**
     * Actualizar un documento existente.
     *
     * @param int    $id             ID del documento
     * @param int    $oportunidad_id ID de la oportunidad asociada
     * @param string $nombre         Nombre del documento (requerido)
     * @param string $url            URL del documento (requerido)
     * @param string $tipo           Tipo del documento
     *
     * @throws Exception Si nombre o URL son inválidos
     */
    public function actualizar($id, $oportunidad_id, $nombre, $url, $tipo) {
        if (empty($nombre) || empty($url)) {
            throw new Exception("Nombre y URL son requeridos");
        }

        // Crear entidad con los datos actualizados
        $documento = new Documento(
            $id,
            $oportunidad_id,
            $nombre,
            $url,
            $tipo,
            null        // La fecha de subida no suele cambiar
        );

        return $this->documentoDAO->actualizar($documento);
    }

    /**
     * Eliminar un documento por su ID.
     *
     * @param int $id ID del documento a eliminar
     */
    public function eliminar($id) {
        return $this->documentoDAO->eliminar($id);
    }
}
