<?php
/**
 * CAPA DE PRESENTACIÓN - dashboard.php
 * Descripción: Página principal del sistema (Dashboard)
 * Propósito: Mostrar resumen de estadísticas y navegación principal
 * Seguridad: Requiere sesión activa para acceder
 */

// Importar configuración y verificar autenticación
require_once 'config.php';
verificarSesion();  // Redirige a login si no hay sesión activa

// Obtener nombre del usuario logueado desde la sesión
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
        <!-- ========================================
             SIDEBAR: Menú de navegación lateral
             ======================================== -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <!-- Logo del sistema -->
                <h2>SGC</h2>
            </div>
            
            <!-- Menú de navegación -->
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

        <!-- ========================================
             CONTENIDO PRINCIPAL
             ======================================== -->
        <main class="main-content">
            <!-- Barra superior con información del usuario -->
            <header class="top-bar">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <!-- Mostrar nombre del usuario logueado (con protección XSS) -->
                    <span class="user-name"><?php echo htmlspecialchars($nombreUsuario); ?></span>
                    <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
                </div>
            </header>

            <div class="content">
                <!-- Sección de bienvenida personalizada -->
                <div class="welcome-section">
                    <h2>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?></h2>
                    <p>Sistema de Gestión de Clientes - Dashboard Principal</p>
                </div>

                <!-- ========================================
                     GRID DE ESTADÍSTICAS
                     Muestra tarjetas con totales de cada módulo
                     Los datos se cargan dinámicamente con JavaScript
                     ======================================== -->
                <div class="stats-grid">
                    <!-- Tarjeta 1: Total de Clientes (color azul) -->
                    <div class="stat-card stat-blue">
                        <h3>Clientes</h3>
                        <p class="stat-number" id="totalClientes">0</p>
                        <a href="clientes.php">Ver todos</a>
                    </div>
                    
                    <!-- Tarjeta 2: Total de Oportunidades (color verde) -->
                    <div class="stat-card stat-green">
                        <h3>Oportunidades</h3>
                        <p class="stat-number" id="totalOportunidades">0</p>
                        <a href="oportunidades.php">Ver todas</a>
                    </div>
                    
                    <!-- Tarjeta 3: Total de Actividades (color rojo) -->
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
        /**
         * Función para cargar estadísticas del dashboard
         * Hace peticiones paralelas a los 3 endpoints para optimizar velocidad
         */
        async function cargarEstadisticas() {
            try {
                // ========================================
                // PETICIONES PARALELAS con Promise.all
                // Esto es más eficiente que hacer 3 peticiones secuenciales
                // ========================================
                const [clientes, oportunidades, actividades] = await Promise.all([
                    fetch('api/clientes.php').then(r => r.json()),
                    fetch('api/oportunidades.php').then(r => r.json()),
                    fetch('api/actividades.php').then(r => r.json())
                ]);

                // Actualizar los contadores en el DOM con los datos obtenidos
                document.getElementById('totalClientes').textContent = clientes.length || 0;
                document.getElementById('totalOportunidades').textContent = oportunidades.length || 0;
                document.getElementById('totalActividades').textContent = actividades.length || 0;
                
            } catch (error) {
                // Si falla alguna petición, mostrar error en consola
                console.error('Error al cargar estadísticas:', error);
            }
        }

        // Ejecutar la función al cargar la página
        cargarEstadisticas();
    </script>
</body>
</html>
