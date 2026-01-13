<?php
// Incluye el archivo de configuración (conexión, funciones comunes, etc.)
require_once 'config.php';

// Incluye la capa de negocio que maneja la lógica relacionada con clientes
require_once __DIR__ . '/../CapaNegocio/ClienteNegocio.php';

// Verifica que exista una sesión iniciada, si no, redirige o bloquea acceso
verificarSesion();

// Obtiene el nombre del usuario autenticado para mostrarlo en la interfaz
$nombreUsuario = obtenerNombreUsuario();

// Instancia la capa de negocio de clientes
$clienteNegocio = new ClienteNegocio();

// Obtiene los tipos de cliente desde la base de datos (para llenar el select)
$tiposCliente = $clienteNegocio->obtenerTiposCliente();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Sistema de Gestión</title>

    <!-- Hoja de estilos CSS principal -->
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="dashboard">

        <!-- Barra lateral del sistema -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>SGC</h2>
            </div>

            <!-- Menú de navegación del sistema -->
            <ul class="nav-menu">
                <li><a href="dashboard.php">Dashboard</a></li>

                <!-- Sección activa resaltada -->
                <li><a href="clientes.php" class="active">Clientes</a></li>

                <li><a href="contactos.php">Contactos</a></li>
                <li><a href="oportunidades.php">Oportunidades</a></li>
                <li><a href="actividades.php">Actividades</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="documentos.php">Documentos</a></li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <main class="main-content">

            <!-- Barra superior con título y datos del usuario -->
            <header class="top-bar">
                <h1>Gestión de Clientes</h1>

                <div class="user-info">
                    <!-- Se usa htmlspecialchars para evitar inyecciones XSS -->
                    <span class="user-name"><?php echo htmlspecialchars($nombreUsuario); ?></span>

                    <!-- Enlace para cerrar la sesión -->
                    <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
                </div>
            </header>

            <div class="content">

                <!-- Barra de acciones con botón para abrir formulario -->
                <div class="action-bar">
                    <button class="btn btn-primary" onclick="mostrarFormulario()">Nuevo Cliente</button>
                </div>

                <!-- Formulario para crear o editar clientes (oculto inicialmente) -->
                <div id="formularioCliente" class="form-container" style="display:none;">
                    <h3 id="tituloForm">Nuevo Cliente</h3>

                    <form id="formCliente">

                        <!-- Campo oculto para almacenar el ID del cliente (en caso de edición) -->
                        <input type="hidden" id="clienteId">

                        <!-- Primera fila del formulario -->
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nombre</label>
                                <!-- Campo obligatorio -->
                                <input type="text" id="nombre" required>
                            </div>

                            <div class="form-group">
                                <label>Tipo Cliente</label>

                                <!-- Select cargado dinámicamente desde la BD -->
                                <select id="tipo_cliente_id" required>
                                    <?php foreach($tiposCliente as $tipo): ?>
                                        <option value="<?php echo $tipo['id']; ?>">
                                            <?php echo $tipo['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Segunda fila del formulario -->
                        <div class="form-row">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="tel" id="telefono">
                            </div>

                            <div class="form-group">
                                <label>Fecha Alta</label>
                                <!-- Fecha de registro del cliente -->
                                <input type="date" id="fecha_alta" required>
                            </div>
                        </div>

                        <!-- Dirección del cliente -->
                        <div class="form-group">
                            <label>Dirección</label>
                            <textarea id="direccion" rows="3"></textarea>
                        </div>

                        <!-- Botones del formulario -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-secondary" onclick="ocultarFormulario()">Cancelar</button>
                        </div>
                    </form>
                </div>

                <!-- Tabla donde se mostrarán los clientes cargados vía JS -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Fecha Alta</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody id="tablaClientes">
                            <!-- Fila temporal mostrada mientras carga la información -->
                            <tr>
                                <td colspan="7" class="text-center">Cargando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Archivo JavaScript que controla la lógica de clientes (carga, CRUD, etc.) -->
    <script src="js/clientes.js"></script>
</body>
</html>
