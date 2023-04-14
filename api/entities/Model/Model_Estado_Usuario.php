<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad generos.
*/
class ModelEstadoUsuarios
{
    /*
    *   Métodos para realizar las operaciones SCRUD (read).
    */

    public function readAll()
    {
        $sql = 'SELECT id_estado_usuario, estado_usuario
        FROM estados_usuarios';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario, alias_usuario, clave_usuario, telefono_usuario, tipo_usuario, id_estado_usuario
        FROM usuarios INNER JOIN estados_usuarios USING(id_estado_usuario) 
                WHERE id_usuario = ?';
        $params = array($this->id_usuario);
        return Database::getRow($sql, $params);
    }
}