<?php
require_once 'config.php';
require_once __DIR__ . '/../CapaNegocio/ClienteNegocio.php';
verificarSesion();
$nombreUsuario = obtenerNombreUsuario();
$clienteNegocio = new ClienteNegocio();
$tiposCliente = $clienteNegocio->obtenerTiposCliente();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Sistema de Gestión</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="dashboard">
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>SGC</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="clientes.php" class="active">Clientes</a></li>
                <li><a href="contactos.php">Contactos</a></li>
                <li><a href="oportunidades.php">Oportunidades</a></li>
                <li><a href="actividades.php">Actividades</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="documentos.php">Documentos</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <header class="top-bar">
                <h1>Gestión de Clientes</h1>
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($nombreUsuario); ?></span>
                    <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
                </div>
            </header>

            <div class="content">
                <div class="action-bar">
                    <button class="btn btn-primary" onclick="mostrarFormulario()">Nuevo Cliente</button>
                </div>

                <div id="formularioCliente" class="form-container" style="display:none;">
                    <h3 id="tituloForm">Nuevo Cliente</h3>
                    <form id="formCliente">
                        <input type="hidden" id="clienteId">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" id="nombre" required>
                            </div>
                            <div class="form-group">
                                <label>Tipo Cliente</label>
                                <select id="tipo_cliente_id" required>
                                    <?php foreach($tiposCliente as $tipo): ?>
                                        <option value="<?php echo $tipo['id']; ?>"><?php echo $tipo['nombre']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="tel" id="telefono">
                            </div>
                            <div class="form-group">
                                <label>Fecha Alta</label>
                                <input type="date" id="fecha_alta" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Dirección</label>
                            <textarea id="direccion" rows="3"></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-secondary" onclick="ocultarFormulario()">Cancelar</button>
                        </div>
                    </form>
                </div>

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
                            <tr>
                                <td colspan="7" class="text-center">Cargando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="js/clientes.js"></script>
</body>
</html>
