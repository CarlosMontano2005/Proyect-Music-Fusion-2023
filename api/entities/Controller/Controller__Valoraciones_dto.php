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
    protected $Id_detalle_pedido = null;
    protected $calificacion_producto = null;
    protected $comentario_producto = null;
    protected $fecha_comentario = null;
    protected $estado_comentario = null;
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
    public function setId_DetallePedido($value)
    {
        if (Validator::validateNaturalNumber($value, 1, 50)) {
            $this->Id_detalle_pedido = $value;
            return true;
        } else {
            return false;
        }
    }
    
    public function setCalificacion_Producto($value)
    {
        if (Validator::validateNaturalNumber($value, 1, 50)) {
            $this->calificacion_producto = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setFecha_Comentario($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha_comentario = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setComentarioProducto($value)
    {
        if (Validator::validateString($value, 1, 200)) {
            $this->comentario_producto = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setEstado($value)
    {
        if (Validator::validateBoolean($value)) {
            $this->estado_comentario = $value;
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
    public function getId_DetallePedido()
    {
        return $this->Id_detalle_pedido;
    }
    public function getCalificacion_Producto()
    {
        return $this->calificacion_producto;
    }
    public function getFecha_Comentario()
    {
        return $this->fecha_comentario;
    }
    public function getComentarioProducto()
    {
        return $this->comentario_producto;
    }
    public function getsetEstado()
    {
        return $this->estado_comentario;
    }

}
