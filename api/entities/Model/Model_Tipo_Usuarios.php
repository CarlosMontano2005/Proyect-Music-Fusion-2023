<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad generos.
*/
class ModelTipoUsuarios
{
    /*
    *   Métodos para realizar las operaciones SCRUD (read).
    */

    public function readAll()
    {
        $sql = 'SELECT id_tipo_usuario, tipo_usuario
        FROM tipos_usuarios';
        return Database::getRows($sql);
    }
    public function firstuse()
    {
        $sql = 'SELECT id_tipo_usuario, tipo_usuario
        FROM tipos_usuarios WHERE  tipo_usuario.model LIKE  '%Administrador%'';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario, alias_usuario, clave_usuario, telefono_usuario, tipo_usuario, id_estado_usuario
        FROM usuarios INNER JOIN tipos_usuarios USING(id_tipo_usuario) 
                WHERE id_usuario = ?';
        $params = array($this->id_usuario);
        return Database::getRow($sql, $params);
    }
}