<?php
require_once('../../helpers/validator.php');
require_once('../../entities/Model/Model_Productos.php');
/*
*	Clase para manejar la transferencia de datos de la entidad USUARIO.
*/
class ControllerProductos extends ModelProductos
{
    // Declaración de atributos (propiedades).
    protected $id_producto = null;
    protected $nombre_producto = null;
    protected $precio_producto = null;
    protected $descripcion = null;
    protected $imagen = null;
    protected $id_marca_producto = null;
    protected $id_categoria_producto = null;
    protected $id_estado_producto = null;
    protected $id_usuario = null;
    protected $ruta = '../../img/productos/';

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId_Producto($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombre_Producto($value)
    {
        if (Validator::validateAlphabetic($value, 1, 50)) {
            $this->nombre_producto = $value;
            return true;
        } else {
            return false;
        }
    }


    public function setPrecio_Producto($value)
    {
        if (Validator::validateMoney($value)) {
            $this->precio_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDescripcion_Producto($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->descripcion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setImagen($file)
    {
        if (Validator::validateImageFile($file, 500, 500)) {
            $this->imagen = Validator::getFileName();
            return true;
        } else {
            return false;
        }
    }

    public function setId_Marca_Producto($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_marca_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_Categoria_Producto($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_categoria_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_Estado_Producto($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_estado_producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_Usuario($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_usuario = $value;
            return true;
        } else {
            return false;
        }
    }


    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId_Producto()
    {
        return $this->id_producto;
    }

    public function getNombre_Producto()
    {
        return $this->nombre_producto;
    }

    public function getPrecio_Producto()
    {
        return $this->precio_producto;
    }

    public function getDescripcion_Producto()
    {
        return $this->descripcion;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function getId_Marca_Producto()
    {
        return $this->id_marca_producto;
    }

    public function getId_Categoria_Producto()
    {
        return $this->id_categoria_producto;
    }

    public function getId_Estado_Producto()
    {
        return $this->id_estado_producto;
    }

    public function getId_Usuario()
    {
        return $this->id_usuario;
    }

    public function getRuta()
    {
        return $this->ruta;
    }
}  