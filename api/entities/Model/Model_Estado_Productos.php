<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad generos.
*/
class ModelEstadoProductos
{
    /*
    *   Métodos para realizar las operaciones SCRUD (read).
    */

    public function readAll()
    {
        $sql = 'SELECT id_estado_producto, estado_producto
        FROM estados_productos';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_producto, nombre_producto, id_marca_producto, precio_producto, id_categoria_producto, descripcion, id_estado_producto, imagen_producto, id_usuario
        FROM productos INNER JOIN estados_productos USING(id_estado_producto)
        WHERE id_producto = ?';
        $params = array($this->id_producto);
        return Database::getRow($sql, $params);
    }
}