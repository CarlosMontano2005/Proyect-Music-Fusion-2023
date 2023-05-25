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
        $sql = 'SELECT id_producto, nombre_producto, id_marca_producto, nombre_marca, precio_producto, id_categoria_producto, nombre_categoria, descripcion, estado_producto, imagen_producto, id_usuario, nombre_usuario, cantidad_producto, fecha_compra
        FROM productos INNER JOIN marcas USING(id_marca_producto)
        INNER JOIN categorias using (id_categoria_producto)
        INNER JOIN usuarios using (id_usuario)
        WHERE nombre_producto ILIKE ? OR cast(precio_producto as varchar) ILIKE ?
        ORDER BY nombre_producto';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }


    public function readAll()
    {
        $sql = 'SELECT id_producto, nombre_producto, id_marca_producto, nombre_marca, precio_producto, id_categoria_producto, nombre_categoria, descripcion, estado_producto, imagen_producto, id_usuario, nombre_usuario, cantidad_producto, fecha_compra
                FROM productos INNER JOIN marcas USING(id_marca_producto)
                INNER JOIN categorias using (id_categoria_producto)
                INNER JOIN usuarios using (id_usuario)
                ORDER BY (id_categoria_producto) asc';
        return Database::getRows($sql);
    }

    public function readProductosCategoria()
    {
        $sql = 'SELECT id_producto, nombre_producto, id_marca_producto, nombre_marca, precio_producto, id_categoria_producto, descripcion, estado_producto, imagen_producto, id_usuario, nombre_usuario, cantidad_producto, fecha_compra
        FROM productos INNER JOIN marcas USING(id_marca_producto)
        INNER JOIN usuarios using (id_usuario)
        WHERE id_categoria_producto = ? AND estado_producto = true
        ORDER BY nombre_producto';
        $params = array($this->id_producto);
        return Database::getRows($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_producto, nombre_producto, id_marca_producto, nombre_marca, precio_producto,
        id_categoria_producto, nombre_categoria, descripcion, estado_producto, 
        imagen_producto, id_usuario, nombre_usuario, cantidad_producto, fecha_compra
                        FROM productos INNER JOIN marcas USING(id_marca_producto)
                        INNER JOIN categorias using (id_categoria_producto)
                        INNER JOIN usuarios using (id_usuario)
                        WHERE id_producto = ?';
        $params = array($this->id_producto);
        return Database::getRow($sql, $params);
    }
    public function readAllComentarios()
    {
        $sql = 'SELECT nombre_cliente, apellido_cliente, nombre_producto, comentario_producto, fecha_comentario
                FROM valoraciones 
                INNER JOIN detalles_pedidos USING(id_detalle_pedido) 
                INNER JOIN pedidos USING(id_pedido) 
                INNER JOIN clientes USING(id_cliente) 
                INNER JOIN productos USING(id_producto) 
                WHERE  id_producto = ?
                GROUP BY id_valoracion ,id_detalle_pedido, id_cliente,id_producto, nombre_producto, comentario_producto,
                fecha_comentario, me_gusta, nombre_cliente,apellido_cliente';
        $params = array($this->id_producto);
        return Database::getRows($sql, $params);
    }
    public function ContarValoracionesProducto()
    {
        $sql = 'SELECT SUM(calificacion_producto)valoracion
                FROM valoraciones
                INNER JOIN detalles_pedidos USING(id_detalle_pedido) 
                INNER JOIN pedidos USING(id_pedido) 
                INNER JOIN clientes USING(id_cliente) 
                INNER JOIN productos USING(id_producto) 
                WHERE id_producto = ?';
        $params = array($this->id_producto);
        return Database::getRow($sql, $params);
    }
    public function ContarLikeProducto()
    {
        $sql = 'SELECT COUNT(id_producto)me_gusta
                FROM valoraciones
                INNER JOIN detalles_pedidos USING(id_detalle_pedido) 
                INNER JOIN pedidos USING(id_pedido) 
                INNER JOIN clientes USING(id_cliente) 
                INNER JOIN productos USING(id_producto) 
                WHERE id_producto = ? AND me_gusta = true';
        $params = array($this->id_producto);
        return Database::getRow($sql, $params);
    }
    public function readPropioComentarios()
    {
        $sql = 'SELECT nombre_cliente, apellido_cliente, nombre_producto, comentario_producto, fecha_comentario
                FROM valoraciones 
                INNER JOIN detalles_pedidos USING(id_detalle_pedido) 
                INNER JOIN pedidos USING(id_pedido) 
                INNER JOIN clientes USING(id_cliente) 
                INNER JOIN productos USING(id_producto) 
                WHERE Id_cliente = ? and id_producto = ?
                GROUP BY id_valoracion ,id_detalle_pedido, id_cliente,id_producto, nombre_producto, comentario_producto,
                fecha_comentario, me_gusta, nombre_cliente,apellido_cliente';
        $params = array($_SESSION['id_cliente'], $this->id_producto);
        return Database::getRows($sql, $params);
    }
    public function createRow()
    {
        $sql = 'INSERT INTO productos(nombre_producto, id_marca_producto, precio_producto, id_categoria_producto, descripcion, imagen_producto, id_usuario, cantidad_producto, fecha_compra)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre_producto, $this->id_marca_producto, $this->precio_producto, $this->id_categoria_producto, $this->descripcion, $this->imagen, $_SESSION['id_usuario'], $this->cantidad_producto, $this->fecha_compra);
        return Database::executeRow($sql, $params);
    }
    public function createRowComentario()
    {
        //verificar si hay insercion
        $sql = 'INSERT INTO valoraciones(id_detalle_pedido, comentario_producto, fecha_comentario)
        VALUES((SELECT MAX(id_detalle_pedido)AS id_detalle_pedido FROM detalles_pedidos 
        INNER JOIN pedidos USING(id_pedido) 
        INNER JOIN estado_pedidos USING(id_estado_pedido) 
        INNER JOIN clientes USING(id_cliente) 
        INNER JOIN productos USING(id_producto) 
        WHERE Id_cliente = ? and id_producto = ?), ?, current_date)';
        $params = array($_SESSION['id_cliente'], $this->id_producto,$this->descripcion);
        if(Database::executeRow($sql, $params)){
            //si hay insercion que actualice los datos
            $sql = 'UPDATE valoraciones
            SET  id_detalle_pedido = 
                    ((SELECT MAX(id_detalle_pedido)AS id_detalle_pedido FROM detalles_pedidos 
                    INNER JOIN pedidos USING(id_pedido) 
                    INNER JOIN estado_pedidos USING(id_estado_pedido) 
                    INNER JOIN clientes USING(id_cliente) 
                    INNER JOIN productos USING(id_producto) 
                    WHERE Id_cliente = ? and id_producto = ?)),
                     comentario_producto= ?, fecha_comentario = current_date
                    WHERE id_valoracion = (SELECT MAX(id_valoracion)AS id_valoracion FROM valoraciones 
                INNER JOIN detalles_pedidos USING(id_detalle_pedido)
                    INNER JOIN pedidos USING(id_pedido) 
                    INNER JOIN clientes USING(id_cliente) 
                    INNER JOIN productos USING(id_producto) 
                    WHERE Id_cliente = ? and id_producto = ?);';
            $params = array($_SESSION['id_cliente'], $this->id_producto,$this->descripcion, $_SESSION['id_cliente'], $this->id_producto);
             if(Database::executeRow($sql, $params)){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
        ;
    }
    
    public function updateRow($current_image)
    {   
        ($this->imagen) ? Validator::deleteFile($this->getRuta(), $current_image) : $this->imagen = $current_image;
        $sql = 'UPDATE productos
                SET nombre_producto=?, id_marca_producto=?, precio_producto=?, id_categoria_producto=?, 
                descripcion=?,  imagen_producto=?, cantidad_producto=?, fecha_compra=?,estado_producto=?
                WHERE  id_producto=?';
        $params = array($this->nombre_producto, $this->id_marca_producto, $this->precio_producto, $this->id_categoria_producto, $this->descripcion, $this->imagen, $this->cantidad_producto, $this->fecha_compra, $this->id_estado_producto, $this->id_producto);
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