<?php
require_once('../../helpers/validator.php');
require_once('../../entities/Model/Model_Tipo_Usuarios.php');

/*
*	Clase para manejar la transferencia de datos de la entidad generos.
*/
class ControllerTipoUsuarios extends ModelTipoUsuarios
{
    //declaracion de atributos 
    protected $id = null;
    protected $tipo_usuario = null;
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
    public function setTipoUsuario($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->tipo_usuario = $value;
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
    
    public function getTipoUsuarios()
    {
        return $this->tipo_usuarios;
    }
}