<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad USUARIO.
*/
class ModelProductos
{
    /*
    *   Métodos para gestionar la cuenta del usuario.
    */
    
    
    public function searchRows($value)
    {
        $sql = 'SELECT id_producto, nombre_producto, id_marca_producto, nombre_marca, precio_producto, id_categoria_producto, nombre_categoria, descripcion, id_estado_producto, estado_producto, imagen_producto, id_usuario, nombre_usuario
        FROM productos INNER JOIN marcas USING(id_marca_producto)
        INNER JOIN estados_productos using (id_estado_producto)
        INNER JOIN categorias using (id_categoria_producto)
        INNER JOIN usuarios using (id_usuario)
        WHERE nombre_producto ILIKE ? OR precio_producto ILIKE ?
        ORDER BY nombre_producto';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_producto, nombre_producto, id_marca_producto, nombre_marca, precio_producto, id_categoria_producto, nombre_categoria, descripcion, id_estado_producto, estado_producto, imagen_producto, id_usuario, nombre_usuario
                FROM productos INNER JOIN marcas USING(id_marca_producto)
				INNER JOIN estados_productos using (id_estado_producto)
                INNER JOIN categorias using (id_categoria_producto)
                INNER JOIN usuarios using (id_usuario)
                ORDER BY (id_producto) asc';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_producto, nombre_producto, id_marca_producto, nombre_marca, precio_producto, id_categoria_producto, nombre_categoria, descripcion, id_estado_producto, estado_producto, imagen_producto, id_usuario, nombre_usuario
                FROM productos INNER JOIN marcas USING(id_marca_producto)
                INNER JOIN estados_productos using (id_estado_producto)
                INNER JOIN categorias using (id_categoria_producto)
                INNER JOIN usuarios using (id_usuario)
                WHERE id_producto = ?';
        $params = array($this->id_producto);
        return Database::getRow($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO productos(nombre_producto, id_marca_producto, precio_producto, id_categoria_producto, descripcion, id_estado_producto, imagen_producto, id_usuario)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_producto, $this->id_marca_producto, $this->precio_producto, $this->id_categoria_producto, $this->descripcion, $this->id_estado_producto, $this->imagen, $this->id_usuario);
        return Database::executeRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE productos 
                SET nombre_producto = ?, id_marca_producto = ?, precio_producto = ?, id_categoria_producto = ?, descripcion = ?, id_estado_producto = ?, id_usuario = ?, imagen_producto = ?
                WHERE id_producto = ?';
        $params = array($this->nombre_producto, $this->id_marca_producto, $this->precio_producto, $this->id_categoria_producto, $this->descripcion, $this->id_estado_producto, $this->id_usuario, $this->imagen, $this->id_producto);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM productos
                WHERE id_producto = ?';
        $params = array($this->id_producto);
        return Database::executeRow($sql, $params);
    }
  

} 