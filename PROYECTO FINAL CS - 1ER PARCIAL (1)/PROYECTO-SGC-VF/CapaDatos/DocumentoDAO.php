<?php
require_once __DIR__ . '/Conexion.php';                    // Importa la clase que gestiona la conexión a la base de datos (PDO)
require_once __DIR__ . '/../CapaEntidades/Documento.php';  // Importa la entidad Documento

/**
 * DAO para Documento
 * Responsable de realizar operaciones CRUD sobre la tabla Documento
 */
class DocumentoDAO {
    private $conexion; // Guarda la instancia de conexión PDO

    public function __construct() {
        // Obtiene la conexión desde la clase Conexion (patrón Singleton)
        $this->conexion = Conexion::getConexion();
    }

    /**
     * Crear un nuevo documento asociado a una oportunidad
     * 
     * @param Documento $documento Objeto Documento con los datos a insertar
     * @return bool True si la inserción fue exitosa, false en caso contrario
     */
    public function crear(Documento $documento) {
        // Sentencia SQL para insertar un nuevo registro en la tabla Documento
        $sql = "INSERT INTO Documento (oportunidad_id, nombre, url, tipo) 
                VALUES (:oportunidad_id, :nombre, :url, :tipo)";
        
        $stmt = $this->conexion->prepare($sql); // Prepara la consulta para ejecución segura
        
        // Ejecuta la sentencia vinculando los valores desde el objeto Documento
        return $stmt->execute([
            ':oportunidad_id' => $documento->getOportunidadId(),
            ':nombre' => $documento->getNombre(),
            ':url' => $documento->getUrl(),
            ':tipo' => $documento->getTipo()
        ]);
    }

    /**
     * Listar todos los documentos
     * Ordenados por fecha de subida (de más reciente a más antiguo)
     * 
     * @return array Lista completa de documentos
     */
    public function listar() {
        // Consulta directa sin parámetros
        $sql = "SELECT * FROM Documento ORDER BY fecha_subida DESC";
        
        $stmt = $this->conexion->query($sql); // Ejecuta la consulta
        return $stmt->fetchAll();             // Devuelve todos los resultados
    }

    /**
     * Obtener un documento por su ID
     * 
     * @param int $id ID del documento
     * @return array|false Datos del documento o false si no existe
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Documento WHERE id = :id"; // Consulta parametrizada
        $stmt = $this->conexion->prepare($sql);          // Prepara la consulta
        $stmt->execute([':id' => $id]);                  // Ejecuta con el valor del ID
        return $stmt->fetch();                           // Retorna un único registro
    }

    /**
     * Actualizar la información de un documento existente
     * 
     * @param Documento $documento Objeto con los datos actualizados
     * @return bool True si la operación fue exitosa
     */
    public function actualizar(Documento $documento) {
        // SQL de actualización con parámetros nombrados
        $sql = "UPDATE Documento SET oportunidad_id = :oportunidad_id, nombre = :nombre, 
                url = :url, tipo = :tipo WHERE id = :id";
        
        $stmt = $this->conexion->prepare($sql); // Prepara la consulta
        
        // Ejecuta la consulta con los datos del objeto
        return $stmt->execute([
            ':id' => $documento->getId(),
            ':oportunidad_id' => $documento->getOportunidadId(),
            ':nombre' => $documento->getNombre(),
            ':url' => $documento->getUrl(),
            ':tipo' => $documento->getTipo()
        ]);
    }

    /**
     * Eliminar un documento por su ID
     * 
     * @param int $id ID del documento a eliminar
     * @return bool True si se eliminó correctamente
     */
    public function eliminar($id) {
        $sql = "DELETE FROM Documento WHERE id = :id"; // Declaración de eliminación
        $stmt = $this->conexion->prepare($sql);        // Prepara la sentencia
        return $stmt->execute([':id' => $id]);         // Ejecuta vinculando el ID
    }
}
