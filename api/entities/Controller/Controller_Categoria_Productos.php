<?php
require_once('../../helpers/validator.php');
require_once('../../entities/Model/Model_Categoria_Productos.php');

/*
*	Clase para manejar la transferencia de datos de la entidad generos.
*/
class ControllerCategoriaProductos extends ModelCategoriaProductos
{
    //declaracion de atributos 
    protected $id_categoria_producto = null;
    protected $nombre_categoria = null;
    //protected $ruta = '../../images/productos/';
    
    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId_Categoria_Producto($value)
    {
        if(Validator::validateNaturalNumber($value)) {
            $this->id_categoria_producto = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setNombre_Categoria($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->nombre_categoria = $value;
            return true;
        } else {
            return false;
        }
    }
   
    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId_Categoria_Producto()
    {
        return $this->id_categoria_producto;
    }
    
    public function getNombre_Categoria()
    {
        return $this->nombre_categoria;
    }
}