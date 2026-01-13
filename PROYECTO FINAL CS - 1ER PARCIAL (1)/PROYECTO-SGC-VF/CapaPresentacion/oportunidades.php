<?php
require_once 'config.php';              // Incluye el archivo de configuración y utilidades generales
verificarSesion();                      // Verifica si el usuario tiene sesión activa
$nombreUsuario = obtenerNombreUsuario(); // Obtiene el nombre del usuario logueado
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">                               <!-- Codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Para diseño responsive -->
    <title>Oportunidades - Sistema de Gestión</title>    <!-- Título de la página -->
    <link rel="stylesheet" href="css/estilos.css">      <!-- Hoja de estilos -->
</head>
<body>
    <div class="dashboard">                             <!-- Contenedor principal del layout -->
        <nav class="sidebar">                           <!-- Barra lateral de navegación -->
            <div class="sidebar-header">
                <h2>SGC</h2>                            <!-- Nombre o logo del sistema -->
            </div>
            <ul class="nav-menu">                       <!-- Menú de navegación -->
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="clientes.php">Clientes</a></li>
                <li><a href="contactos.php">Contactos</a></li>
                <li><a href="oportunidades.php" class="active">Oportunidades</a></li> <!-- Página activa -->
                <li><a href="actividades.php">Actividades</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="documentos.php">Documentos</a></li>
            </ul>
        </nav>

        <main class="main-content">                    <!-- Contenido principal -->
            <header class="top-bar">                   <!-- Barra superior -->
                <h1>Gestión de Oportunidades</h1>       <!-- Título principal -->
                <div class="user-info">                <!-- Información del usuario -->
                    <span class="user-name"><?php echo htmlspecialchars($nombreUsuario); ?></span> <!-- Nombre del usuario -->
                    <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a> <!-- Botón de logout -->
                </div>
            </header>

            <div class="content">                      <!-- Contenedor del contenido -->
                <div class="toolbar">                  <!-- Barra de herramientas -->
                    <button class="btn btn-primary" onclick="mostrarFormulario()">Nueva Oportunidad</button> <!-- Botón para mostrar formulario -->
                </div>

                <div id="formularioOportunidad" class="form-container" style="display:none;">
                    <!-- Formulario oculto inicialmente para agregar o editar oportunidad -->
                    <h3>Agregar Oportunidad</h3>
                    <form id="formOportunidad">
                        <input type="hidden" id="oportunidadId" name="id"> <!-- Campo oculto para edición -->

                        <div class="form-group">
                            <label for="cliente_id">Cliente *</label>
                            <select id="cliente_id" name="cliente_id" required>
                                <option value="">Seleccione un cliente</option> <!-- Lista de clientes cargada dinámicamente -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="estado_oportunidad_id">Estado *</label>
                            <select id="estado_oportunidad_id" name="estado_oportunidad_id" required>
                                <option value="1">EN PROCESO</option>
                                <option value="2">GANADA</option>
                                <option value="3">PERDIDA</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha_hora">Fecha y Hora *</label>
                            <input type="datetime-local" id="fecha_hora" name="fecha_hora" required> <!-- Fecha y hora de la oportunidad -->
                        </div>

                        <div class="form-group">
                            <label for="monto">Monto</label>
                            <input type="number" id="monto" name="monto" step="0.01" value="0"> <!-- Valor monetario de la oportunidad -->
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" rows="3"></textarea> <!-- Detalles adicionales -->
                        </div>

                        <div class="form-actions"> <!-- Botones del formulario -->
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-secondary" onclick="ocultarFormulario()">Cancelar</button>
                        </div>
                    </form>
                </div>

                <div class="table-container"> <!-- Contenedor de la tabla de oportunidades -->
                    <table class="data-table"> <!-- Tabla con listado de oportunidades -->
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Descripción</th>
                                <th>Acciones</th> <!-- Editar / Eliminar -->
                            </tr>
                        </thead>
                        <tbody id="tablaOportunidades">
                            <tr>
                                <td colspan="7" class="text-center">Cargando datos...</td> <!-- Mensaje mientras se cargan los datos -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="js/oportunidades.js"></script> <!-- Script que maneja CRUD y la interacción del formulario -->
</body>
</html>
