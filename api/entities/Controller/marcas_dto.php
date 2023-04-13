<?php
require_once('../../helpers/validator.php');
require_once('../../entities/Model/marcas_queries.php');
/*
*	Clase para manejar la transferencia de datos de la entidad CATEGORIA.
*/
class Marcas extends MarcasQueries
{
    // Declaración de atributos (propiedades).
    protected $id = null;
    protected $nombre = null;
    protected $imagen = null;
    //protected $descripcion = null;
    protected $ruta = '../../img/marcas/';

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombre($value)
    {
        if (Validator::validateAlphanumeric($value, 1, 50)) {
            $this->nombre = $value;
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

    

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function getRuta()
    {
        return $this->ruta;
    }
}
