<?php
require_once('../../helpers/validator.php');
require_once('../../entities/Model/clientes_queries.php');

/*
*	Clase para manejar la transferencia de datos de la entidad Clientes.
*/
class Cliente extends ClienteQueries
{
    //declaracion de atributos 
    protected $id = null;
    
    protected $nombre_cliente = null;
    protected $apellido_cliente = null;
    protected $correo_cliente = null;
    protected $fecha_nacimiento = null;
    protected $dui_cliente = null;
    protected $id_genero = null;
    protected $telefono_cliente = null;
    protected $clave  = null;
    protected $estado  = null;
    protected $direccion_cliente  = null;
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
    public function setNombre($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->nombre_cliente = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setApellido($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->apellido_cliente = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setId_genero($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_genero = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setCorreo($value)
    {
        if (Validator::validateEmail($value)) {
            $this->correo_cliente = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTelefono($value)
    {
        if (Validator::validatePhone($value)) {
            $this->telefono_cliente = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDUI($value)
    {
        if (Validator::validateDUI($value)) {
            $this->dui_cliente = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNacimiento($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha_nacimiento = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDireccion($value)
    {
        if (Validator::validateString($value, 1, 200)) {
            $this->direccion_cliente = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setClave($value)
    {
        if (Validator::validatePassword($value)) {
            $this->clave = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            return false;
        }
    }

    public function setEstado($value)
    {
        if (Validator::validateBoolean($value)) {
            $this->estado = $value;
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
    public function getId_genero()
    {
        return $this->id_genero;
    }
    public function getNombres()
    {
        return $this->nombre_cliente;
    }

    public function getApellidos()
    {
        return $this->apellido_cliente;
    }

    public function getCorreo()
    {
        return $this->correo_cliente;
    }

    public function getTelefono()
    {
        return $this->telefono_cliente;
    }

    public function getDUI()
    {
        return $this->dui_cliente;
    }

    public function getNacimiento()
    {
        return $this->fecha_nacimiento;
    }

    public function getDireccion()
    {
        return $this->direccion_cliente;
    }

    public function getClave()
    {
        return $this->clave;
    }

    public function getEstado()
    {
        return $this->estado;
    }
}
