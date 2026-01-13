<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../CapaEntidades/Oportunidad.php';

/**
 * DAO para Oportunidad
 * 
 * Esta clase encapsula todas las operaciones de acceso a datos relacionadas
 * con la tabla Oportunidad en la base de datos.
 */
class OportunidadDAO {
    private $conexion;

    public function __construct() {
        // Obtiene la conexión PDO mediante la clase Conexion
        $this->conexion = Conexion::getConexion();
    }

    /**
     * Crea una nueva oportunidad en la base de datos.
     */
    public function crear(Oportunidad $oportunidad) {
        $sql = "INSERT INTO Oportunidad (cliente_id, estado_oportunidad_id, fecha_hora, monto, descripcion) 
                VALUES (:cliente_id, :estado_oportunidad_id, :fecha_hora, :monto, :descripcion)";
        
        // Prepara la sentencia SQL para evitar inyecciones
        $stmt = $this->conexion->prepare($sql);

        // Ejecuta la consulta con los valores de la entidad Oportunidad
        return $stmt->execute([
            ':cliente_id' => $oportunidad->getClienteId(),
            ':estado_oportunidad_id' => $oportunidad->getEstadoOportunidadId(),
            ':fecha_hora' => $oportunidad->getFechaHora(),
            ':monto' => $oportunidad->getMonto(),
            ':descripcion' => $oportunidad->getDescripcion()
        ]);
    }

    /**
     * Obtiene todas las oportunidades con información relacionada
     * del cliente y del estado de la oportunidad.
     */
    public function listar() {
        $sql = "SELECT o.*, c.nombre as cliente_nombre, eo.nombre as estado_nombre 
                FROM Oportunidad o 
                INNER JOIN Cliente c ON o.cliente_id = c.id 
                INNER JOIN EstadoOportunidad eo ON o.estado_oportunidad_id = eo.id 
                ORDER BY o.fecha_hora DESC";

        // Ejecuta la consulta directamente porque no hay parámetros dinámicos
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Obtiene una oportunidad por su ID.
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Oportunidad WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);

        // Ejecuta con el ID enviado
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    /**
     * Actualiza una oportunidad existente.
     */
    public function actualizar(Oportunidad $oportunidad) {
        $sql = "UPDATE Oportunidad 
                SET cliente_id = :cliente_id, 
                    estado_oportunidad_id = :estado_oportunidad_id, 
                    fecha_hora = :fecha_hora, 
                    monto = :monto, 
                    descripcion = :descripcion 
                WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':id' => $oportunidad->getId(),
            ':cliente_id' => $oportunidad->getClienteId(),
            ':estado_oportunidad_id' => $oportunidad->getEstadoOportunidadId(),
            ':fecha_hora' => $oportunidad->getFechaHora(),
            ':monto' => $oportunidad->getMonto(),
            ':descripcion' => $oportunidad->getDescripcion()
        ]);
    }

    /**
     * Elimina una oportunidad por su ID.
     */
    public function eliminar($id) {
        $sql = "DELETE FROM Oportunidad WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Obtiene todos los estados posibles de una oportunidad.
     */
    public function obtenerEstados() {
        $sql = "SELECT * FROM EstadoOportunidad ORDER BY nombre";

        // Retorna todos los resultados directamente
        return $this->conexion->query($sql)->fetchAll();
    }
}
