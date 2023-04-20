<?php
require_once('../../helpers/validator.php');
require_once('../../entities/Model/Model_Usuarios_Productos.php');

/*
*	Clase para manejar la transferencia de datos de la entidad generos.
*/
class ControllerUsuarioProducto extends ModelUsuariosProductos
{
    //declaracion de atributos 
    protected $id_usuario_producto = null;
    protected $nombre_usuario = null;
    //protected $ruta = '../../images/productos/';
    
    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId_Usuario_Producto($value)
    {
        if(Validator::validateNaturalNumber($value)) {
            $this->id_usuario_producto = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setNombre_Usuario($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->nombre_usuario = $value;
            return true;
        } else {
            return false;
        }
    }
   
    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId_Usuario_Producto()
    {
        return $this->id_usuario_producto;
    }
    
    public function getNombre_Usuario()
    {
        return $this->nombre_usuario;
    }
}