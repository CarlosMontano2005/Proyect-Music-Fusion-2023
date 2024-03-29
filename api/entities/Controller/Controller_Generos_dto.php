<?php
require_once('../../helpers/validator.php');
require_once('../../entities/Model/Model_Generos_queries.php');

/*
*	Clase para manejar la transferencia de datos de la entidad generos.
*/
class Genero extends GenerosQueries
{
    //declaracion de atributos 
    protected $id_genero = null;
    protected $genero = null;
    //protected $ruta = '../../images/productos/';
    
    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if(Validator::validateNaturalNumber($value)) {
            $this->id_genero = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setGenero($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->genero = $value;
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
        return $this->id_genero;
    }
    
    public function getGeneros()
    {
        return $this->genero;
    }
}