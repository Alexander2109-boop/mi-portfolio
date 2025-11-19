<?php
require_once 'config.php';
verificarSesion();
$nombreUsuario = obtenerNombreUsuario();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactos - Sistema de Gestión</title>
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
                <li><a href="clientes.php">Clientes</a></li>
                <li><a href="contactos.php" class="active">Contactos</a></li>
                <li><a href="oportunidades.php">Oportunidades</a></li>
                <li><a href="actividades.php">Actividades</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="documentos.php">Documentos</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <header class="top-bar">
                <h1>Gestión de Contactos</h1>
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($nombreUsuario); ?></span>
                    <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
                </div>
            </header>

            <div class="content">
                <div class="toolbar">
                    <button class="btn btn-primary" onclick="mostrarFormulario()">Nuevo Contacto</button>
                </div>

                <div id="formularioContacto" class="form-container" style="display:none;">
                    <h3>Agregar Contacto</h3>
                    <form id="formContacto">
                        <input type="hidden" id="contactoId" name="id">
                        
                        <div class="form-group">
                            <label for="cliente_id">Cliente *</label>
                            <select id="cliente_id" name="cliente_id" required>
                                <option value="">Seleccione un cliente</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nombre">Nombre *</label>
                            <input type="text" id="nombre" name="nombre" required>
                        </div>

                        <div class="form-group">
                            <label for="cargo">Cargo</label>
                            <input type="text" id="cargo" name="cargo">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email">
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono">
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
                                <th>Cliente</th>
                                <th>Nombre</th>
                                <th>Cargo</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaContactos">
                            <tr>
                                <td colspan="7" class="text-center">Cargando datos...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="js/contactos.js"></script>
</body>
</html>
