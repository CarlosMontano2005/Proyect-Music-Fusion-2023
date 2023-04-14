<?php
require_once('../../helpers/validator.php');
require_once('../../entities/Model/Model_Usuarios.php');
/*
*	Clase para manejar la transferencia de datos de la entidad USUARIO.
*/
class ControllerUsuarios extends ModelUsuarios
{
    // Declaración de atributos (propiedades).
    protected $id_usuario = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $correo = null;
    protected $alias = null;
    protected $clave = null;
    protected $telefono = null;
    protected $id_tipo_usuario = null;
    protected $id_estado_usuario = null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId_Usuario($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_usuario = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombre_Usuario($value)
    {
        if (Validator::validateAlphabetic($value, 1, 50)) {
            $this->nombre = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setApellido_Usuario($value)
    {
        if (Validator::validateAlphabetic($value, 1, 50)) {
            $this->apellido = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCorreo_Usuario($value)
    {
        if (Validator::validateEmail($value)) {
            $this->correo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setAlias_Usuario($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->alias = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setClave_Usuario($value)
    {
        if (Validator::validatePassword($value)) {
            $this->clave = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            return false;
        }
    }

    public function setTelefono_Usuario($value){

        if(validator::validatePhone($value)){
            $this->telefono = $value;
            return true;
        }else{

            return false;
        }

    }

    public function setId_tipo_usuario($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_tipo_usuario = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setId_estado_usuario($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_estado_usuario = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId_Usuario()
    {
        return $this->id_usuario;
    }

    public function getNombre_Usuario()
    {
        return $this->nombre;
    }

    public function getApellido_Usuario()
    {
        return $this->apellido;
    }

    public function getCorreo_Usuario()
    {
        return $this->correo;
    }

    public function getAlias_Usuario()
    {
        return $this->alias;
    }

    public function getClave_Usuario()
    {
        return $this->clave;
    }

    public function getTelefono_Usuario(){

        return $this->telefono;
    }

    public function getId_tipo_usuario(){

        return $this->id_tipo_usuario;
    }

    public function getId_estado_usuario(){

        return $this->id_estado_usuario;
    }
}  