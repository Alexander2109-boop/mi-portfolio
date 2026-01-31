<?php 
// 1. Esto DEBE ser lo primero: inicia la sesión antes de enviar cualquier HTML al navegador
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Académico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="headers.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <header class="p-3 mb-3 border-bottom bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between">

                <a href="index.php" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <span class="fs-4">Sistema Académico</span>
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 ms-lg-4">
                    <li><a href="index.php" class="nav-link px-2 text-secondary">Inicio</a></li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Estudiantes
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="registrar_estudiante.php">Registrar</a></li>
                            <li><a class="dropdown-item" href="index.php?accion=reporte">Consultar</a></li>
                        </ul>
                    </li>

                    <li><a href="cursos.php" class="nav-link px-2 text-white">Cursos</a></li>
                    <li><a href="docentes.php" class="nav-link px-2 text-white">Docente</a></li>
                </ul>

                <div class="d-flex align-items-center">
                    <div class="text-end">
                        <?php 
                        // 2. Verificamos si existe la sesión que creamos en el Controlador
                        if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])): ?>
                            <span class="text-white me-3">Hola, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong></span>
                            <a href="index.php?accion=logout" class="btn btn-outline-danger btn-sm">Salir</a>
                        <?php else: ?>
                            <a href="index.php?accion=login" class="btn btn-outline-light me-2">Entrar</a>
                            <a href="index.php?accion=registro" class="btn btn-warning">Registrarse</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-shrink-0">