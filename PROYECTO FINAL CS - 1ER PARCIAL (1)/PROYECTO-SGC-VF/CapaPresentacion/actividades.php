<?php
// Incluye el archivo de configuración con funciones comunes
require_once 'config.php';

// Verifica que el usuario tenga una sesión activa
verificarSesion();

// Obtiene el nombre del usuario para mostrarlo en la interfaz
$nombreUsuario = obtenerNombreUsuario();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades - Sistema de Gestión</title>

    <!-- Hoja de estilos CSS principal -->
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="dashboard">
        <!-- Barra lateral del dashboard -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>SGC</h2>
            </div>

            <!-- Menú de navegación -->
            <ul class="nav-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="clientes.php">Clientes</a></li>
                <li><a href="contactos.php">Contactos</a></li>
                <li><a href="oportunidades.php">Oportunidades</a></li>

                <!-- Sección actual activa -->
                <li><a href="actividades.php" class="active">Actividades</a></li>

                <li><a href="productos.php">Productos</a></li>
                <li><a href="documentos.php">Documentos</a></li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <main class="main-content">
            <header class="top-bar">
                <!-- Título de la página -->
                <h1>Gestión de Actividades</h1>

                <!-- Información del usuario y botón de salida -->
                <div class="user-info">
                    <!-- Se usa htmlspecialchars para evitar XSS -->
                    <span class="user-name"><?php echo htmlspecialchars($nombreUsuario); ?></span>
                    <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
                </div>
            </header>

            <div class="content">

                <!-- Barra de herramientas, botón para abrir el formulario -->
                <div class="toolbar">
                    <button class="btn btn-primary" onclick="mostrarFormulario()">Nueva Actividad</button>
                </div>

                <!-- Formulario para crear o editar actividades (oculto por defecto) -->
                <div id="formularioActividad" class="form-container" style="display:none;">
                    <h3>Agregar Actividad</h3>
                    <form id="formActividad">
                        <!-- Campo oculto para el ID (solo para edición) -->
                        <input type="hidden" id="actividadId" name="id">
                        
                        <div class="form-group">
                            <label for="oportunidad_id">Oportunidad *</label>

                            <!-- Lista de oportunidades que se cargará dinámicamente -->
                            <select id="oportunidad_id" name="oportunidad_id" required>
                                <option value="">Seleccione una oportunidad</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tipo_actividad_id">Tipo de Actividad *</label>

                            <!-- Tipos predefinidos -->
                            <select id="tipo_actividad_id" name="tipo_actividad_id" required>
                                <option value="1">LLAMADA</option>
                                <option value="2">EMAIL</option>
                                <option value="3">REUNION</option>
                                <option value="4">OTRO</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha_hora">Fecha y Hora *</label>

                            <!-- Selector de fecha y hora -->
                            <input type="datetime-local" id="fecha_hora" name="fecha_hora" required>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>

                            <!-- Campo de texto para detalles de la actividad -->
                            <textarea id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>

                        <!-- Botones del formulario -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-secondary" onclick="ocultarFormulario()">Cancelar</button>
                        </div>
                    </form>
                </div>

                <!-- Tabla donde se mostrarán las actividades -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Oportunidad</th>
                                <th>Tipo</th>
                                <th>Fecha y Hora</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody id="tablaActividades">
                            <!-- Fila temporal mientras se cargan los datos -->
                            <tr>
                                <td colspan="6" class="text-center">Cargando datos...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Archivo JavaScript que maneja la lógica de las actividades -->
    <script src="js/actividades.js"></script>
</body>
</html>
