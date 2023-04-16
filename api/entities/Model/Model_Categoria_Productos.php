<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad generos.
*/
class ModelCategoriaProductos
{
    /*
    *   Métodos para realizar las operaciones SCRUD (read).
    */

    public function readAll()
    {
        $sql = 'SELECT id_categoria_producto, nombre_categoria
        FROM categorias';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_producto, nombre_producto, id_marca_producto, precio_producto, id_categoria_producto, descripcion, id_estado_producto, imagen_producto, id_usuario
        FROM productos INNER JOIN categorias USING(id_categoria_producto)
        WHERE id_producto = ?';
        $params = array($this->id_producto);
        return Database::getRow($sql, $params);
    }
}