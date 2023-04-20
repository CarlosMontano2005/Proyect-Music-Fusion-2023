<?php
require_once('../../helpers/validator.php');
require_once('../../entities/Model/Model_Marca_Producto.php');

/*
*	Clase para manejar la transferencia de datos de la entidad generos.
*/
class ControllerMarcaProductos extends ModelMarcaProductos
{
    //declaracion de atributos 
    protected $id_marca_producto = null;
    protected $nombre_marca = null;
    //protected $ruta = '../../images/productos/';
    
    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId_Marca_Producto($value)
    {
        if(Validator::validateNaturalNumber($value)) {
            $this->id_marca_producto = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setNombre_Marca($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->nombre_marca = $value;
            return true;
        } else {
            return false;
        }
    }
   
    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId_Marca_Producto()
    {
        return $this->id_marca_producto;
    }
    
    public function getNombre_Marca()
    {
        return $this->nombre_marca;
    }
}