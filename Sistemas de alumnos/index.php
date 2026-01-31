<?php
require_once 'controlador/UsuarioControlador.php';
require_once 'controlador/AlumnoControlador.php';
// primer paso obtener la accion

$accion = $_GET['accion'] ?? 'inicio';

$usuarioCtrl = new UsuarioControlador();
$alumnoCtrl = new AlumnoControlador();
switch ($accion) {
    case 'login':
        // Capturamos el error
        $error = isset($_GET['error']) ? "Usuario o contraseña incorrectos" : "";
        include 'vista/login.php';
        break;

    case 'procesarLogin':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioCtrl->procesarLogin($_POST['usuario'], $_POST['clave']);
        }
        break;

    case 'logout':
        // Limpiamos la sesión y regresamos al inicio
        session_start();
        session_destroy();
        header("Location: index.php?accion=inicio");
        exit;
        break;
    case 'reporte': // Acción para ver y buscar alumnos
        $alumnoCtrl->reporte();
        break;

    case 'verAlumno':
        $alumnoCtrl->verDetalle($_GET['id']);
        break;

    case 'eliminarAlumno':
        $alumnoCtrl->eliminar($_GET['id']);
        break;

    case 'menu':
        include 'vista/menu.php';
        break;

    case 'inicio':
    default:

        include 'vista/inicio.php';
        break;
}
