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
    <title>Dashboard - Sistema de Gestión</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="dashboard">
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>SGC</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="clientes.php">Clientes</a></li>
                <li><a href="contactos.php">Contactos</a></li>
                <li><a href="oportunidades.php">Oportunidades</a></li>
                <li><a href="actividades.php">Actividades</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="documentos.php">Documentos</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <header class="top-bar">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($nombreUsuario); ?></span>
                    <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
                </div>
            </header>

            <div class="content">
                <div class="welcome-section">
                    <h2>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?></h2>
                    <p>Sistema de Gestión de Clientes - Dashboard Principal</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card stat-blue">
                        <h3>Clientes</h3>
                        <p class="stat-number" id="totalClientes">0</p>
                        <a href="clientes.php">Ver todos</a>
                    </div>
                    
                    <div class="stat-card stat-green">
                        <h3>Oportunidades</h3>
                        <p class="stat-number" id="totalOportunidades">0</p>
                        <a href="oportunidades.php">Ver todas</a>
                    </div>
                    
                    <div class="stat-card stat-red">
                        <h3>Actividades</h3>
                        <p class="stat-number" id="totalActividades">0</p>
                        <a href="actividades.php">Ver todas</a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Cargar estadísticas básicas
        async function cargarEstadisticas() {
            try {
                const [clientes, oportunidades, actividades] = await Promise.all([
                    fetch('api/clientes.php').then(r => r.json()),
                    fetch('api/oportunidades.php').then(r => r.json()),
                    fetch('api/actividades.php').then(r => r.json())
                ]);

                document.getElementById('totalClientes').textContent = clientes.length || 0;
                document.getElementById('totalOportunidades').textContent = oportunidades.length || 0;
                document.getElementById('totalActividades').textContent = actividades.length || 0;
            } catch (error) {
                console.error('Error al cargar estadísticas:', error);
            }
        }

        cargarEstadisticas();
    </script>
</body>
</html>
