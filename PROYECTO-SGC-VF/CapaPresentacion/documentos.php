<?php
require_once 'config.php';              // Importa el archivo de configuración, donde probablemente se maneja la conexión y utilidades.
verificarSesion();                      // Verifica si el usuario tiene una sesión activa; si no, lo redirige.
$nombreUsuario = obtenerNombreUsuario(); // Obtiene el nombre del usuario actualmente logueado.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">                               <!-- Configuración de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive -->
    <title>Documentos - Sistema de Gestión</title>       <!-- Título de la pestaña -->
    <link rel="stylesheet" href="css/estilos.css">      <!-- Hoja de estilos -->
</head>
<body>
    <div class="dashboard">                             <!-- Contenedor general del layout -->
        <nav class="sidebar">                           <!-- Barra lateral de navegación -->
            <div class="sidebar-header">
                <h2>SGC</h2>                            <!-- Nombre del sistema -->
            </div>
            <ul class="nav-menu">                       <!-- Lista de enlaces del menú -->
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="clientes.php">Clientes</a></li>
                <li><a href="contactos.php">Contactos</a></li>
                <li><a href="oportunidades.php">Oportunidades</a></li>
                <li><a href="actividades.php">Actividades</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="documentos.php" class="active">Documentos</a></li> <!-- Página actual -->
            </ul>
        </nav>

        <main class="main-content">                    <!-- Contenido principal -->
            <header class="top-bar">                   <!-- Barra superior -->
                <h1>Gestión de Documentos</h1>         <!-- Título principal -->
                <div class="user-info">                <!-- Info del usuario -->
                    <span class="user-name"><?php echo htmlspecialchars($nombreUsuario); ?></span> <!-- Nombre del usuario -->
                    <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a> <!-- Cerrar sesión -->
                </div>
            </header>

            <div class="content">                      <!-- Contenedor del contenido -->
                <div class="toolbar">                  <!-- Herramientas superiores -->
                    <button class="btn btn-primary" onclick="mostrarFormulario()">Nuevo Documento</button>
                    <!-- Botón para desplegar el formulario -->
                </div>

                <div id="formularioDocumento" class="form-container" style="display:none;">
                    <!-- Formulario oculto inicialmente -->
                    <h3>Agregar Documento</h3>
                    <form id="formDocumento">
                        <input type="hidden" id="documentoId" name="id"> <!-- Campo oculto para editar -->

                        <div class="form-group">
                            <label for="oportunidad_id">Oportunidad *</label>
                            <select id="oportunidad_id" name="oportunidad_id" required>
                                <option value="">Seleccione una oportunidad</option> <!-- Se cargará dinámicamente -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nombre">Nombre *</label>
                            <input type="text" id="nombre" name="nombre" required> <!-- Nombre del documento -->
                        </div>

                        <div class="form-group">
                            <label for="url">URL del Documento *</label>
                            <input type="text" id="url" name="url" required> <!-- Enlace del documento -->
                        </div>

                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <input type="text" id="tipo" name="tipo" placeholder="PDF, Word, Excel, etc."> <!-- Tipo de archivo -->
                        </div>

                        <div class="form-actions"> <!-- Botones del formulario -->
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-secondary" onclick="ocultarFormulario()">Cancelar</button>
                        </div>
                    </form>
                </div>

                <div class="table-container"> <!-- Contenedor de la tabla -->
                    <table class="data-table"> <!-- Tabla de documentos -->
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Oportunidad</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Fecha Subida</th>
                                <th>Acciones</th> <!-- Editar / Eliminar -->
                            </tr>
                        </thead>
                        <tbody id="tablaDocumentos">
                            <tr>
                                <td colspan="6" class="text-center">Cargando datos...</td> <!-- Mensaje inicial -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="js/documentos.js"></script> <!-- Script que maneja CRUD con fetch -->
</body>
</html>
