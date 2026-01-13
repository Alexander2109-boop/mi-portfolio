<?php
/**
 * Entidad Producto
 * Representa un producto dentro del sistema, con información básica como nombre,
 * descripción, precio y si está activo o no.
 */
class Producto {
    // Identificador único del producto
    private $id;

    // Nombre del producto
    private $nombre;

    // Descripción del producto
    private $descripcion;

    // Precio del producto
    private $precio;

    // Estado del producto (activo/inactivo)
    private $activo;

    /**
     * Constructor de la clase Producto
     * Inicializa los atributos del producto, permitiendo valores por defecto
     */
    public function __construct($id = null, $nombre = '', $descripcion = '', $precio = 0, $activo = true) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->activo = $activo;
    }

    // Getter para obtener el ID del producto
    public function getId() { return $this->id; }

    // Setter para modificar el ID del producto
    public function setId($id) { $this->id = $id; }
    
    // Getter para obtener el nombre del producto
    public function getNombre() { return $this->nombre; }

    // Setter para modificar el nombre del producto
    public function setNombre($nombre) { $this->nombre = $nombre; }
    
    // Getter para obtener la descripción del producto
    public function getDescripcion() { return $this->descripcion; }

    // Setter para modificar la descripción del producto
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    
    // Getter para obtener el precio del producto
    public function getPrecio() { return $this->precio; }

    // Setter para modificar el precio del producto
    public function setPrecio($precio) { $this->precio = $precio; }
    
    // Getter para saber si el producto está activo
    public function getActivo() { return $this->activo; }

    // Setter para modificar el estado activo/inactivo del producto
    public function setActivo($activo) { $this->activo = $activo; }
}
