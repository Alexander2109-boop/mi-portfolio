<?php
require_once __DIR__ . '/../dao/AlumnoDao.php';

class AlumnoControlador
{
    private $dao;

    public function __construct()
    {
        $this->dao = new AlumnoDao();
    }

    public function reporte()
    {
        // Capturamos filtros
        $tipo = $_GET['tipo_busqueda'] ?? '';
        $valor = $_GET['valor_busqueda'] ?? null;

        if ($valor) {
            if ($tipo == 'apellido') {
                $alumnos = $this->dao->buscarPorApellido($valor);
            } else {
                $alumnos = $this->dao->buscarPorNombre($valor);
            }
        } else {
            $alumnos = $this->dao->obtenerTodos();
        }

        $cantidad = count($alumnos);
        // Cargamos la vista de consulta
        require_once __DIR__ . '/../vista/alumno/consultar.php';
    }

    public function mostrarFormulario($mensaje = "")
    {
        require_once __DIR__ . '/../vista/alumno/registrar.php';
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'cedula' => $_POST['cedula'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'apellido' => $_POST['apellido'] ?? '',
                'correo' => $_POST['correo'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'fechaNacimiento' => $_POST['fecha_nacimiento'] ?? ''
            ];

            $exito = $this->dao->guardar($datos);
            $mensaje = $exito ? "Alumno registrado correctamente" : "Error al registrar";
            $this->mostrarFormulario($mensaje);
        }
    }
    public function verDetalle($id)
    {
        $alumno = $this->dao->obtenerPorId($id);
        require_once __DIR__ . '/../vista/alumno/detalle.php';
        if ($alumno) {
            require_once __DIR__ . '/../vista/alumno/detalle.php';
        } else {
            header("Location: index.php?accion=reporte&error=no_encontrado");
        }
    }

    public function eliminar($id)
    {
        $this->dao->eliminar($id);
        // Después de eliminar, regresamos al listado
        header("Location: index.php?accion=reporte");
    }
}
