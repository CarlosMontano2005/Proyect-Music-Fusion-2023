<?php
require_once('../../helpers/validator.php');
require_once('../../entities/Model/Model_detalles_pedidos_queries.php');

/*
*	Clase para manejar la transferencia de datos de la entidad Clientes.
*/
class Detalles_Pedidos extends Detalles_Pedidos_Queries
{
    //declaracion de atributos 
    protected $id = null;
    protected $id_pedido = null;
    protected $id_producto = null;
    protected $cantidad_producto = null;
    protected $precio_detalle_producto = null;
    //protected $ruta = '../../images/productos/';
    
    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if(Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setId_Pedido($value)
    {
        if (Validator::validateNaturalNumber($value, 1, 50)) {
            $this->id_pedido = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setId_Producto($value)
    {
        if (Validator::validateNaturalNumber($value, 1, 50)) {
            $this->id_producto = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setCantidad_Producto($value)
    {
        if (Validator::validateNaturalNumber($value, 1, 50)) {
            $this->cantidad_producto = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setPrecio_detalle_Producto($value)
    {
        if (Validator::validateMoney($value)) {
            $this->precio_detalle_producto = $value;
            return true;
        } else {
            return false;
        }
    }



   
    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId()
    {
        return $this->id;
    }
    public function getId_Pedido()
    {
        return $this->id_pedido;
    }
    public function getId_Producto()
    {
        return $this->id_producto;
    }
    public function getCantidad_Producto()
    {
        return $this->cantidad_producto;
    }
    public function getPrecio_detalle_Producto()
    {
        return $this->precio_detalle_producto;
    }

    
}
