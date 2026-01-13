<?php
require_once 'config.php';              // Incluye archivo de configuración y utilidades generales
verificarSesion();                      // Verifica si hay sesión activa; si no, redirige o bloquea acceso
$nombreUsuario = obtenerNombreUsuario(); // Obtiene el nombre del usuario logueado
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">                               <!-- Codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Diseño responsive -->
    <title>Productos - Sistema de Gestión</title>       <!-- Título de la página -->
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
                <li><a href="oportunidades.php">Oportunidades</a></li>
                <li><a href="actividades.php">Actividades</a></li>
                <li><a href="productos.php" class="active">Productos</a></li> <!-- Página activa -->
                <li><a href="documentos.php">Documentos</a></li>
            </ul>
        </nav>

        <main class="main-content">                    <!-- Contenido principal -->
            <header class="top-bar">                   <!-- Barra superior -->
                <h1>Gestión de Productos</h1>           <!-- Título principal -->
                <div class="user-info">                <!-- Información del usuario -->
                    <span class="user-name"><?php echo htmlspecialchars($nombreUsuario); ?></span> <!-- Nombre del usuario -->
                    <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a> <!-- Botón de cerrar sesión -->
                </div>
            </header>

            <div class="content">                      <!-- Contenedor del contenido -->
                <div class="toolbar">                  <!-- Barra de herramientas -->
                    <button class="btn btn-primary" onclick="mostrarFormulario()">Nuevo Producto</button> <!-- Botón para mostrar formulario -->
                </div>

                <div id="formularioProducto" class="form-container" style="display:none;">
                    <!-- Formulario oculto inicialmente para agregar o editar producto -->
                    <h3>Agregar Producto</h3>
                    <form id="formProducto">
                        <input type="hidden" id="productoId" name="id"> <!-- Campo oculto para edición -->

                        <div class="form-group">
                            <label for="nombre">Nombre *</label>
                            <input type="text" id="nombre" name="nombre" required> <!-- Nombre del producto -->
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" rows="3"></textarea> <!-- Descripción del producto -->
                        </div>

                        <div class="form-group">
                            <label for="precio">Precio</label>
                            <input type="number" id="precio" name="precio" step="0.01" value="0"> <!-- Precio del producto -->
                        </div>

                        <div class="form-group">
                            <label for="activo">Estado</label>
                            <select id="activo" name="activo"> <!-- Estado del producto: activo o inactivo -->
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>

                        <div class="form-actions"> <!-- Botones del formulario -->
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-secondary" onclick="ocultarFormulario()">Cancelar</button>
                        </div>
                    </form>
                </div>

                <div class="table-container"> <!-- Contenedor de la tabla de productos -->
                    <table class="data-table"> <!-- Tabla con listado de productos -->
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th>Acciones</th> <!-- Editar / Eliminar -->
                            </tr>
                        </thead>
                        <tbody id="tablaProductos">
                            <tr>
                                <td colspan="6" class="text-center">Cargando datos...</td> <!-- Mensaje inicial mientras se cargan datos -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="js/productos.js"></script> <!-- Script que maneja CRUD y la interacción del formulario -->
</body>
</html>
