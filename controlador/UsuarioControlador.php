<?php
require_once __DIR__ . '/../dao/UsuarioDao.php';

class UsuarioControlador{
    public function procesarLogin($usuario ,$clave){
        if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
        $userDao = new UsuarioDao();
        $userobj = $userDao->autenticar($usuario, $clave);
        if($userobj){
            session_start();
            $_SESSION['usuario'] = $userobj->getUsuario();
            header("Location: index.php?accion=menu");
        }else   {
            header("Location: index.php?accion=login&error=1");
        }exit;
        
    }


}


?>