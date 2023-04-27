<?php
require_once('../../helpers/validator.php');
require_once('../../entities/Model/Model_pedidos.php');
/*
*	Clase para manejar la transferencia de datos de la entidad USUARIO.
*/
class ControllerPedidos extends ModelPedidos
{
    // Declaración de atributos (propiedades).
    protected $id_pedido = null;
    protected $fecha_pedido = null;
    protected $direccion_pedido = null;
    protected $id_cliente = null;
    protected $id_estado_pedido = null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId_Pedido($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_pedido = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setFecha_Pedido($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha_pedido = $value;
            return true;
        } else {
            return false;
        }
    }


    public function setDireccion_Pedido($value)
    {
        if (Validator::validateString($value, 1, 200)) {
            $this->direccion_pedido = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_Cliente($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_cliente = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_Estado_Pedido($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_estado_pedido = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId_Pedido()
    {
        return $this->id_pedido;
    }

    public function getFecha_Pedido()
    {
        return $this->fecha_pedido;
    }

    public function getDireccion_Pedido()
    {
        return $this->direccion_pedido;
    }

    public function getId_Cliente()
    {
        return $this->id_cliente;
    }

    public function getId_Estado_Pedido()
    {
        return $this->id_estado_pedido;
    }

    public function getId_Marca_Producto()
    {
        return $this->id_marca_producto;
    }

}  