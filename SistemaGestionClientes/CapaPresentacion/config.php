<?php
// Iniciar sesión
session_start();

// Función para verificar si el usuario está autenticado
function verificarSesion() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.html');
        exit;
    }
}

// Función para obtener el nombre del usuario actual
function obtenerNombreUsuario() {
    return isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'Usuario';
}
