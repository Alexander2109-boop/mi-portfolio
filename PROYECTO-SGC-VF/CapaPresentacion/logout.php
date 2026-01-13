<?php
session_start();            // Inicia la sesión actual para poder acceder a sus datos
session_destroy();          // Destruye toda la sesión, eliminando variables de sesión y cerrando sesión del usuario
header('Location: index.html'); // Redirige al usuario a la página de inicio (index.html)
exit;                       // Termina la ejecución del script para asegurar que no se ejecute nada más
