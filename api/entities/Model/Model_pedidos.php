<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad PRODUCTO.
*/
class ModelPedidos
{
    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    public function searchRows($value)
    {
        $sql = 'SELECT id_pedido, estado_pedido, fecha_pedido, direccion_pedido, nombre_cliente
        FROM pedidos INNER JOIN estado_pedidos USING(id_estado_pedido)
		INNER JOIN clientes USING(id_cliente)   
		WHERE  cast(id_pedido as varchar) ILIKE ? OR nombre_cliente ILIKE ?
		ORDER BY (id_pedido);';
        $params = array( "%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_pedido, estado_pedido, fecha_pedido, direccion_pedido, nombre_cliente
        FROM pedidos INNER JOIN estado_pedidos USING(id_estado_pedido)
		INNER JOIN clientes USING(id_cliente) 
		ORDER BY (id_pedido);';
        return Database::getRows($sql);
    }

    public function readAllEstadoPedido()
    {
        $sql = 'SELECT id_estado_pedido, estado_pedido
        FROM estado_pedidos;';
        return Database::getRows($sql);
    }
    public function readAllClientes()
    {
        $sql = 'SELECT id_cliente,nombre_cliente, apellido_cliente, correo_cliente, fecha_nacimiento, dui, id_genero, telefono_cliente, clave, estado, direccion_cliente
        FROM clientes;';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_pedido, estado_pedido, fecha_pedido, direccion_pedido, nombre_cliente
        FROM pedidos INNER JOIN estado_pedidos USING(id_estado_pedido)
		INNER JOIN clientes USING(id_cliente) 
		WHERE id_pedido = ?';
        $params = array($this->id_pedido);
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
        if($this->imagen) {
            if($current_image != 'default.jpg') {
                Validator::deleteFile($this->getRuta(), $current_image);
            }
        } else {
            $this->imagen = $current_image;
        }
        
        $sql = 'UPDATE pedidos
        SET fecha_pedido=?, direccion_pedido=?, id_cliente=?, id_estado_pedido=?
	    WHERE id_pedido=?';
        $params = array($this->fecha_pedido, $this->direccion_pedido, $this->id_cliente, $this->id_estado_pedido, $this->id_pedido);
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

    /*
    *   Métodos para la grafica de pastel.
    */

    public function porcentajePedidosEstado()
    {
        $sql = 'SELECT estado_pedido, ROUND(
            (COUNT(id_pedido) * 100.0 / (SELECT COUNT(id_pedido) FROM pedidos)), 2
                                    ) porcentaje
                        FROM pedidos
                        INNER JOIN estado_pedidos USING(id_estado_pedido)
                        GROUP BY estado_pedido ORDER BY porcentaje DESC';
        return Database::getRows($sql);
    }
}
