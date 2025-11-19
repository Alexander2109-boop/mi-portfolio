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
    <title>Documentos - Sistema de Gestión</title>
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
                <li><a href="contactos.php">Contactos</a></li>
                <li><a href="oportunidades.php">Oportunidades</a></li>
                <li><a href="actividades.php">Actividades</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="documentos.php" class="active">Documentos</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <header class="top-bar">
                <h1>Gestión de Documentos</h1>
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($nombreUsuario); ?></span>
                    <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
                </div>
            </header>

            <div class="content">
                <div class="toolbar">
                    <button class="btn btn-primary" onclick="mostrarFormulario()">Nuevo Documento</button>
                </div>

                <div id="formularioDocumento" class="form-container" style="display:none;">
                    <h3>Agregar Documento</h3>
                    <form id="formDocumento">
                        <input type="hidden" id="documentoId" name="id">
                        
                        <div class="form-group">
                            <label for="oportunidad_id">Oportunidad *</label>
                            <select id="oportunidad_id" name="oportunidad_id" required>
                                <option value="">Seleccione una oportunidad</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nombre">Nombre *</label>
                            <input type="text" id="nombre" name="nombre" required>
                        </div>

                        <div class="form-group">
                            <label for="url">URL del Documento *</label>
                            <input type="text" id="url" name="url" required>
                        </div>

                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <input type="text" id="tipo" name="tipo" placeholder="PDF, Word, Excel, etc.">
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
                                <th>Oportunidad</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Fecha Subida</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaDocumentos">
                            <tr>
                                <td colspan="6" class="text-center">Cargando datos...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="js/documentos.js"></script>
</body>
</html>
