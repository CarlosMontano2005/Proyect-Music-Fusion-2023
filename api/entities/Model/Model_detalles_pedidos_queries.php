<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad PRODUCTO.
*/
class Detalles_Pedidos_Queries
{
    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    public function searchRows($value)
    {
        $sql = 'SELECT id_detalle_pedido, id_pedido, nombre_producto, cantidad_detalle_producto, precio_detalle_producto
        FROM detalles_pedidos INNER JOIN productos USING(id_producto)
     WHERE  cast(id_pedido as varchar) ILIKE ? OR nombre_producto ILIKE ?
                 ORDER BY id_pedido';
        $params = array( "%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_detalle_pedido, id_pedido, nombre_producto, cantidad_detalle_producto, precio_detalle_producto
        FROM detalles_pedidos INNER JOIN productos USING(id_producto) ORDER BY (id_pedido) ;';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_detalle_pedido, id_pedido, nombre_producto, cantidad_detalle_producto, precio_detalle_producto
        FROM detalles_pedidos INNER JOIN productos USING(id_producto)   
        WHERE id_detalle_pedido = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
    
    public function readOneValoracion()
    {
        $sql = 'SELECT id_detalle_pedido, id_pedido, nombre_producto, cantidad_detalle_producto, precio_detalle_producto
        FROM detalles_pedidos INNER JOIN productos USING(id_producto) WHERE id_detalle_pedido = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO clientes(nombre_cliente, apellido_cliente, correo_cliente, 
        fecha_nacimiento, dui, id_genero, telefono_cliente, clave, 
        estado, direccion_cliente)
VALUES (?,?,?,?,?, ?, ?,?, ?, ?)';
        $params = array($this->nombre_cliente, $this->apellido_cliente, $this->correo_cliente, $this->fecha_nacimiento, $this->dui_cliente, $this->id_genero, $this->telefono_cliente, $this->clave, $this->estado, $this->direccion_cliente);
        return Database::executeRow($sql, $params);
    }

    public function updateRow()/*$current_image*/
    {
        // Se verifica si existe una nueva imagen para borrar la actual, de lo contrario se mantiene la actual.
        /*($this->imagen) ? Validator::deleteFile($this->getRuta(), $current_image) : $this->imagen = $current_image;*/

        $sql = 'UPDATE detalles_pedidos
        SET id_pedido=?, id_producto=?, cantidad_detalle_producto=?, precio_detalle_producto=?
        WHERE id_detalle_pedido =?;';
        $params = array($this->id_pedido, $this->id_producto, $this->cantidad_producto, $this->precio_detalle_producto, $this->id);
        return Database::executeRow($sql, $params);
    }
    public function startOrder()
    {
        $sql = 'SELECT id_pedido
                FROM pedidos
                WHERE id_estado_pedido = 4 AND id_cliente = ?';
        $params = array($_SESSION['id_cliente']);
        if ($data = Database::getRow($sql, $params)) {
            $this->id_pedido = $data['id_pedido'];
            return true;
        } else {
            $sql = 'INSERT INTO pedidos(direccion_pedido, id_cliente)
                    VALUES((SELECT direccion_cliente FROM clientes WHERE id_cliente = ?), ?)';
            $params = array($_SESSION['id_cliente'], $_SESSION['id_cliente']);
            // Se obtiene el ultimo valor insertado en la llave primaria de la tabla pedidos.
            if ($this->id_pedido = Database::getLastRow($sql, $params)) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function readOrderDetail()
    {
        $sql = 'SELECT id_detalle_pedido, nombre_producto, detalles_pedidos.precio_detalle_producto, detalles_pedidos.cantidad_detalle_producto
                    FROM pedidos INNER JOIN detalles_pedidos USING(id_pedido) INNER JOIN productos USING(id_producto)
                    WHERE id_pedido = ?	';
        $params = array($this->id_pedido);
        return Database::getRows($sql, $params);
    }


    public function createDetail()
    {
        // Se realiza una subconsulta para obtener el precio del producto.
        $sql = 'INSERT INTO detalles_pedidos(id_producto, precio_detalle_producto, cantidad_detalle_producto, id_pedido)
                VALUES(?, (SELECT precio_producto FROM productos WHERE id_producto = ?), ?, ?)';
        $params = array($this->id_producto, $this->id_producto, $this->cantidad_producto, $this->id_pedido);
        return Database::executeRow($sql, $params);
    }
    public function deleteRow()
    {
        $sql = 'DELETE FROM clientes
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function readProductosCategoria()
    {
        $sql = 'SELECT id_producto, imagen_producto, nombre_producto, descripcion_producto, precio_producto
                FROM productos INNER JOIN categorias USING(id_categoria)
                WHERE id_categoria = ? AND estado_producto = true
                ORDER BY nombre_producto';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }
    
    /*
    *   Métodos para generar gráficas.
    */
    public function cantidadProductosCategoria()
    {
        $sql = 'SELECT nombre_categoria, COUNT(id_producto) cantidad
                FROM productos INNER JOIN categorias USING(id_categoria)
                GROUP BY nombre_categoria ORDER BY cantidad DESC';
        return Database::getRows($sql);
    }

    public function porcentajeProductosCategoria()
    {
        $sql = 'SELECT nombre_categoria, ROUND((COUNT(id_producto) * 100.0 / (SELECT COUNT(id_producto) FROM productos)), 2) porcentaje
                FROM productos INNER JOIN categorias USING(id_categoria)
                GROUP BY nombre_categoria ORDER BY porcentaje DESC';
        return Database::getRows($sql);
    }

    /*
    *   Métodos para generar reportes.
    */
    public function productosCategoria()
    {
        $sql = 'SELECT nombre_producto, precio_producto, estado_producto
                FROM productos INNER JOIN categorias USING(id_categoria)
                WHERE id_categoria = ?
                ORDER BY nombre_producto';
        $params = array($this->categoria);
        return Database::getRows($sql, $params);
    }
}
