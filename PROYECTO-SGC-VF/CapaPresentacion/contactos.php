<?php
// Incluye el archivo de configuración general (conexión, funciones auxiliares, etc.)
require_once 'config.php';

// Verifica que el usuario tenga la sesión iniciada, de lo contrario bloquea el acceso
verificarSesion();

// Obtiene el nombre del usuario autenticado para mostrarlo en la parte superior
$nombreUsuario = obtenerNombreUsuario();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuración estándar de codificación y vista -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Título mostrado en la pestaña del navegador -->
    <title>Contactos - Sistema de Gestión</title>

    <!-- Archivo CSS principal del sistema -->
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="dashboard">

        <!-- Barra lateral del sistema -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>SGC</h2>
            </div>

            <!-- Menú principal de navegación -->
            <ul class="nav-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="clientes.php">Clientes</a></li>

                <!-- Sección actual marcada como activa -->
                <li><a href="contactos.php" class="active">Contactos</a></li>

                <li><a href="oportunidades.php">Oportunidades</a></li>
                <li><a href="actividades.php">Actividades</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="documentos.php">Documentos</a></li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <main class="main-content">

            <!-- Encabezado superior -->
            <header class="top-bar">
                <h1>Gestión de Contactos</h1>

                <!-- Información del usuario y botón de cierre de sesión -->
                <div class="user-info">
                    <!-- htmlspecialchars evita posibles ataques XSS -->
                    <span class="user-name"><?php echo htmlspecialchars($nombreUsuario); ?></span>
                    <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
                </div>
            </header>

            <div class="content">

                <!-- Barra de herramientas con el botón para agregar contacto -->
                <div class="toolbar">
                    <button class="btn btn-primary" onclick="mostrarFormulario()">Nuevo Contacto</button>
                </div>

                <!-- Formulario para crear o editar contactos, oculto inicialmente -->
                <div id="formularioContacto" class="form-container" style="display:none;">
                    <h3>Agregar Contacto</h3>

                    <!-- Formulario de registro/edición -->
                    <form id="formContacto">

                        <!-- Campo oculto para el ID del contacto (solo se usa al editar) -->
                        <input type="hidden" id="contactoId" name="id">
                        
                        <!-- Selección del cliente asociado -->
                        <div class="form-group">
                            <label for="cliente_id">Cliente *</label>
                            <!-- Este select se rellena dinámicamente con JS -->
                            <select id="cliente_id" name="cliente_id" required>
                                <option value="">Seleccione un cliente</option>
                            </select>
                        </div>

                        <!-- Nombre del contacto -->
                        <div class="form-group">
                            <label for="nombre">Nombre *</label>
                            <input type="text" id="nombre" name="nombre" required>
                        </div>

                        <!-- Cargo o rol del contacto dentro de la empresa -->
                        <div class="form-group">
                            <label for="cargo">Cargo</label>
                            <input type="text" id="cargo" name="cargo">
                        </div>

                        <!-- Email de contacto -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email">
                        </div>

                        <!-- Teléfono del contacto -->
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono">
                        </div>

                        <!-- Botones de acción del formulario -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-secondary" onclick="ocultarFormulario()">Cancelar</button>
                        </div>
                    </form>
                </div>

                <!-- Tabla donde se mostrarán los contactos -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Nombre</th>
                                <th>Cargo</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody id="tablaContactos">
                            <!-- Fila momentánea mientras se cargan los datos via JS -->
                            <tr>
                                <td colspan="7" class="text-center">Cargando datos...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Archivo JavaScript donde se maneja el CRUD de contactos -->
    <script src="js/contactos.js"></script>
</body>
</html>
