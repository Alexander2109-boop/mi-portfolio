<?php
/**
 * CAPA DE ENTIDADES - Usuario.php
 * Descripción: Clase que representa la entidad Usuario del sistema
 * Propósito: Encapsular los datos de un usuario (POJO/Bean)
 * Patrón de Diseño: Entidad de Datos (Data Entity)
 */
class Usuario {
    // Atributos privados - Encapsulación de datos
    private $id;
    private $nombre;
    private $email;
    private $password;
    private $rol_id;         // Clave foránea a la tabla Rol
    private $activo;         // Estado del usuario (activo/inactivo)

    /**
     * Constructor de la clase Usuario
     * @param int|null $id - Identificador único del usuario
     * @param string $nombre - Nombre completo del usuario
     * @param string $email - Correo electrónico (único)
     * @param string $password - Contraseña (sin encriptar según requerimiento)
     * @param int|null $rol_id - ID del rol asignado (1=Admin, 2=Vendedor, 3=Supervisor)
     * @param bool $activo - Estado activo/inactivo del usuario
     */
    public function __construct($id = null, $nombre = '', $email = '', $password = '', $rol_id = null, $activo = true) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->rol_id = $rol_id;
        $this->activo = $activo;
    }

    // Métodos Getters - Permiten acceder a los atributos privados
    public function getId() { 
        return $this->id; 
    }
    
    public function getNombre() { 
        return $this->nombre; 
    }
    
    public function getEmail() { 
        return $this->email; 
    }
    
    public function getPassword() { 
        return $this->password; 
    }
    
    public function getRolId() { 
        return $this->rol_id; 
    }
    
    public function getActivo() { 
        return $this->activo; 
    }

    // Métodos Setters - Permiten modificar los atributos privados
    public function setId($id) { 
        $this->id = $id; 
    }
    
    public function setNombre($nombre) { 
        $this->nombre = $nombre; 
    }
    
    public function setEmail($email) { 
        $this->email = $email; 
    }
    
    public function setPassword($password) { 
        $this->password = $password; 
    }
    
    public function setRolId($rol_id) { 
        $this->rol_id = $rol_id; 
    }
    
    public function setActivo($activo) { 
        $this->activo = $activo; 
    }
}
