<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad CATEGORIA.
*/
class MarcasQueries
{
    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    public function searchRows($value)
    {
        $sql = 'SELECT id_marca_producto, nombre_marca, logo_marca
                FROM marcas
                WHERE nombre_marca ILIKE ?
                ORDER BY nombre_marca';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO marcas(nombre_marca, logo_marca)
            VALUES (?, ?)';
        $params = array($this->nombre, $this->imagen);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_marca_producto, nombre_marca, logo_marca
        FROM marcas ORDER BY  (id_marca_producto) asc ';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_marca_producto, nombre_marca, logo_marca
        FROM marcas
        WHERE id_marca_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow($current_image)
    {
        // Se verifica si existe una nueva imagen para borrar la actual, de lo contrario se mantiene la actual.
        ($this->imagen) ? Validator::deleteFile($this->getRuta(), $current_image) : $this->imagen = $current_image;

        $sql = 'UPDATE marcas
        SET   logo_marca=?, nombre_marca=?
        WHERE id_marca_producto = ?';
        $params = array($this->imagen, $this->nombre,$this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM marcas
                WHERE id_marca_producto = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
