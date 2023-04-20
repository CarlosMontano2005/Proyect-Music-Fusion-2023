<?php
require_once('../../helpers/validator.php');
require_once('../../entities/Model/Model_Estado_Productos.php');

/*
*	Clase para manejar la transferencia de datos de la entidad generos.
*/
class ControllerEstadoProductos extends ModelEstadoProductos
{
    //declaracion de atributos 
    protected $id_estado_producto = null;
    protected $estado_producto = null;
    //protected $ruta = '../../images/productos/';
    
    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId_Estado_Producto($value)
    {
        if(Validator::validateNaturalNumber($value)) {
            $this->id_estado_producto = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setEstado_Producto($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->estado_producto = $value;
            return true;
        } else {
            return false;
        }
    }
   
    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId_Estado_Producto()
    {
        return $this->id_estado_producto;
    }
    
    public function getEstado_Producto()
    {
        return $this->estado_producto;
    }
}