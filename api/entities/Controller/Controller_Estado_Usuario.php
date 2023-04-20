<?php
require_once('../../helpers/validator.php');
require_once('../../entities/Model/Model_Estado_Usuario.php');

/*
*	Clase para manejar la transferencia de datos de la entidad generos.
*/
class ControllerEstadoUsuarios extends ModelEstadoUsuarios
{
    //declaracion de atributos 
    protected $id = null;
    protected $estado_usuario = null;
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
    public function setEstadoUsuario($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->estado_usuario = $value;
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
    
    public function getEstadoUsuario()
    {
        return $this->estado_usuario;
    }
}