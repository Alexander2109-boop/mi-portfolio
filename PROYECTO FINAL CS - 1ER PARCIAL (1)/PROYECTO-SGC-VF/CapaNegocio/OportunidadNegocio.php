<?php
require_once __DIR__ . '/../CapaDatos/OportunidadDAO.php';

/**
 * Capa de Negocio para Oportunidad
 *
 * Esta clase aplica validaciones y reglas de negocio antes de delegar
 * operaciones CRUD a la capa de datos (OportunidadDAO).
 */
class OportunidadNegocio {
    private $oportunidadDAO;

    /**
     * Constructor:
     * Instancia el DAO encargado de manejar operaciones con la BD.
     */
    public function __construct() {
        $this->oportunidadDAO = new OportunidadDAO();
    }

    /**
     * Crear una nueva oportunidad comercial.
     *
     * @param int    $cliente_id             ID del cliente asociado
     * @param int    $estado_oportunidad_id  Estado inicial de la oportunidad
     * @param string $fecha_hora             Fecha/hora de creación o registro
     * @param float  $monto                  Monto estimado de la oportunidad
     * @param string $descripcion            Descripción o notas internas
     *
     * @throws Exception Si los campos obligatorios están vacíos
     */
    public function crear($cliente_id, $estado_oportunidad_id, $fecha_hora, $monto, $descripcion) {
        // Validación obligatoria
        if (empty($cliente_id) || empty($estado_oportunidad_id)) {
            throw new Exception("Cliente y estado son requeridos");
        }

        // Construcción de la entidad Oportunidad
        $oportunidad = new Oportunidad(
            null,                   // ID autoincremental
            $cliente_id,
            $estado_oportunidad_id,
            $fecha_hora,
            $monto,
            $descripcion
        );

        // Delegación al DAO
        return $this->oportunidadDAO->crear($oportunidad);
    }

    /**
     * Listar todas las oportunidades registradas.
     *
     * @return array Arreglo de objetos Oportunidad
     */
    public function listar() {
        return $this->oportunidadDAO->listar();
    }

    /**
     * Obtener una oportunidad por su ID.
     *
     * @param int $id ID de la oportunidad
     */
    public function obtenerPorId($id) {
        return $this->oportunidadDAO->obtenerPorId($id);
    }

    /**
     * Actualizar una oportunidad existente.
     *
     * @param int    $id                     ID de la oportunidad
     * @param int    $cliente_id             Cliente asociado
     * @param int    $estado_oportunidad_id  Estado actualizado
     * @param string $fecha_hora             Fecha/hora de modificación
     * @param float  $monto                  Nuevo monto
     * @param string $descripcion            Nueva descripción
     *
     * @throws Exception Si los campos obligatorios están vacíos
     */
    public function actualizar($id, $cliente_id, $estado_oportunidad_id, $fecha_hora, $monto, $descripcion) {
        if (empty($cliente_id) || empty($estado_oportunidad_id)) {
            throw new Exception("Cliente y estado son requeridos");
        }

        // Crear entidad actualizada
        $oportunidad = new Oportunidad(
            $id,
            $cliente_id,
            $estado_oportunidad_id,
            $fecha_hora,
            $monto,
            $descripcion
        );

        return $this->oportunidadDAO->actualizar($oportunidad);
    }

    /**
     * Eliminar una oportunidad por ID.
     *
     * @param int $id ID de la oportunidad
     */
    public function eliminar($id) {
        return $this->oportunidadDAO->eliminar($id);
    }

    /**
     * Obtener la lista de estados disponibles para una oportunidad.
     *
     * @return array
     */
    public function obtenerEstados() {
        return $this->oportunidadDAO->obtenerEstados();
    }
}
