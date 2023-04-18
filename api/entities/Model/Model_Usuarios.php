<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class ModelUsuarios
{
    /*
    *   Métodos para gestionar la cuenta del usuario.
    */
    public function checkUser($alias)
    {
        $sql = 'SELECT id_usuario FROM usuarios WHERE alias_usuario = ?';
        $params = array($alias);
        if ($data = Database::getRow($sql, $params)) {
            $this->id_usuario = $data['id_usuario'];
            $this->alias = $alias;
            return true;
        } else {
            return false;
        }
    }

    public function checkPassword($password)
    {
        $sql = 'SELECT clave_usuario FROM usuarios WHERE id_usuario = ?';
        $params = array($this->id_usuario);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if ($password == $data['clave_usuario']) {
            return true;
        } else {
            return false;
        }
    }
    
    public function searchRows($value)
    {
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario, clave_usuario, telefono_usuario, id_tipo_usuario, id_estado_usuario, alias_usuario, foto
        FROM usuarios INNER JOIN tipos_usuarios USING(id_tipo_usuario)
                WHERE nombre_usuario ILIKE ? OR apellido_usuario ILIKE ?
                ORDER BY nombre_usuario';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario, clave_usuario, telefono_usuario, id_tipo_usuario, id_estado_usuario, alias_usuario, foto
                FROM usuarios INNER JOIN tipos_usuarios USING(id_tipo_usuario)
                ORDER BY nombre_usuario asc';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario, clave_usuario, telefono_usuario, id_tipo_usuario, id_estado_usuario, alias_usuario, foto
                FROM usuarios INNER JOIN tipos_usuarios USING(id_tipo_usuario)
                WHERE id_usuario = ?';
        $params = array($this->id_usuario);
        return Database::getRow($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO usuarios(nombre_usuario, apellido_usuario, correo_usuario, clave_usuario, telefono_usuario, id_tipo_usuario, id_estado_usuario, alias_usuario, foto)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->clave, $this->telefono, $this->id_tipo_usuario, $this->id_estado_usuario, $this->alias, $this->foto);
        return Database::executeRow($sql, $params);
    }

    public function updateRow($current_image)
    {
        // Se verifica si existe una nueva imagen para borrar la actual, de lo contrario se mantiene la actual.
        ($this->foto) ? Validator::deleteFile($this->getRuta(), $current_image) : $this->foto = $current_image;

        $sql = 'UPDATE usuarios 
                SET nombre_usuario = ?, apellido_usuario = ?, correo_usuario = ?, telefono_usuario = ?, id_tipo_usuario = ?, id_estado_usuario  = ?, foto = ?
                WHERE id_usuario = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->telefono, $this->id_tipo_usuario, $this->id_estado_usuario, $this->foto, $this->id_usuario);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM usuarios
                WHERE id_usuario = ?';
        $params = array($this->id_usuario);
        return Database::executeRow($sql, $params);
    }

    

} 