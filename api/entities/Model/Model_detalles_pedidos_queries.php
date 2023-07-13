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
    /*Leer los datos */
    public function readAll()
    {
        $sql = 'SELECT id_detalle_pedido, id_pedido, nombre_producto, cantidad_detalle_producto, precio_detalle_producto
        FROM detalles_pedidos INNER JOIN productos USING(id_producto)  ORDER BY (id_pedido)   asc ;';
        return Database::getRows($sql);
    }
    /**leer un dato para la taba de ordenes*/
    public function readAllOrdenes()
    {
        $sql = 'SELECT id_pedido, estado_pedido, fecha_pedido, direccion_pedido, nombre_cliente,apellido_cliente
        FROM pedidos INNER JOIN estado_pedidos USING(id_estado_pedido)
		INNER JOIN clientes USING(id_cliente) 
		 WHERE id_cliente = ?';
        $params = array($_SESSION['id_cliente']);
        return Database::getRows($sql, $params);
    }
    /**leer un dato por medio de id */
    public function readOne()
    {
        $sql = 'SELECT id_detalle_pedido, id_pedido, nombre_producto, cantidad_detalle_producto, precio_detalle_producto
        FROM detalles_pedidos INNER JOIN productos USING(id_producto)   
        WHERE id_detalle_pedido = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
    /*metodo de valoraciones */
    public function readOneValoracion()
    {
        $sql = 'SELECT id_detalle_pedido, id_pedido, nombre_producto, cantidad_detalle_producto, precio_detalle_producto
        FROM detalles_pedidos INNER JOIN productos USING(id_producto) WHERE id_detalle_pedido = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
    /*agregar datos a la tabla */
    public function createRow()
    {
        $sql = 'INSERT INTO clientes(nombre_cliente, apellido_cliente, correo_cliente, 
        fecha_nacimiento, dui, id_genero, telefono_cliente, clave, 
        estado, direccion_cliente)
VALUES (?,?,?,?,?, ?, ?,?, ?, ?)';
        $params = array($this->nombre_cliente, $this->apellido_cliente, $this->correo_cliente, $this->fecha_nacimiento, $this->dui_cliente, $this->id_genero, $this->telefono_cliente, $this->clave, $this->estado, $this->direccion_cliente);
        return Database::executeRow($sql, $params);
    }
    
/*actualizar datos */
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
    /*agregar datos a la tabla del pedido para realizar un pedido ya sea nuevo o no */
    public function startOrder()
    {
        /*VER SI HAY DATOS EN LAS TABLAS  con el id del cliente*/
        $sql = 'SELECT id_pedido
                FROM pedidos
                WHERE id_estado_pedido = 4 AND id_cliente = ?';
        $params = array($_SESSION['id_cliente']);
        if ($data = Database::getRow($sql, $params)) {
            $this->id_pedido = $data['id_pedido'];
            return true;
        } else {
            /*SI NO HAY DATOS QUE HAGA UNA INSERCION DE ELLOS */
            $sql = 'INSERT INTO pedidos(direccion_pedido, id_cliente, fecha_pedido)
            VALUES((SELECT direccion_cliente FROM clientes WHERE id_cliente = ?), ?, current_date)';
            $params = array($_SESSION['id_cliente'], $_SESSION['id_cliente']);
            // Se obtiene el ultimo valor insertado en la llave primaria de la tabla pedidos.
            if ($this->id_pedido = Database::getLastRow($sql, $params)) {
                return true;
            } else {
                return false;
            }
        }
    }
    /*ver las ordenes en el carrito */
    public function readOrderDetail()
    {
        $sql = 'SELECT ROW_NUMBER() OVER(
            ORDER BY id_detalle_pedido, nombre_producto) AS fila, id_detalle_pedido, id_producto,nombre_producto, imagen_producto,detalles_pedidos.precio_detalle_producto, detalles_pedidos.cantidad_detalle_producto, fecha_pedido
                    FROM pedidos INNER JOIN detalles_pedidos USING(id_pedido) INNER JOIN productos USING(id_producto)
                    WHERE id_pedido = ?';
        $params = array($this->id_pedido);
        return Database::getRows($sql, $params);
    }

     // Método para finalizar un pedido por parte del cliente.
     public function finishOrder()
     {
         // Se establece la zona horaria local para obtener la fecha del servidor.
         date_default_timezone_set('America/El_Salvador');
         $date = date('Y-m-d');
         $this->estado = 1;
         $sql = 'UPDATE pedidos
                    SET id_estado_pedido = ?, fecha_pedido = ?
                    WHERE id_pedido = ?';
         $params = array($this->estado, $date, $_SESSION['id_pedido']);
         if( Database::executeRow($sql, $params)){
            return true;
         }
         else{
            return false;
         }
         
     }
 
    // Método para eliminar un producto que se encuentra en el carrito de compras.
    public function deleteDetail()
    {
        $sql = 'DELETE FROM detalles_pedidos
                        WHERE id_detalle_pedido = ? AND id_pedido = ?';
        $params = array($this->id, $_SESSION['id_pedido']);
        if($data = Database::executeRow($sql, $params)){
            $sql = 'UPDATE productos
            SET cantidad_producto = ((cantidad_producto + ?))
            WHERE  Id_producto = ?;';
            $params = array($this->cantidad_producto, $this->id_producto);
             if($data = Database::executeRow($sql, $params)){
                return true;
            }
        }
        else{
            return false;
        }
        ;
    }
     // Método para actualizar la cantidad de un producto agregado al carrito de compras.
     public function updateDetail()
     {
         $sql = 'UPDATE detalles_pedidos
                    SET cantidad_detalle_producto = ?
                    WHERE id_detalle_pedido = ? AND id_pedido = ?';
         $params = array($this->cantidad_producto, $this->id, $_SESSION['id_pedido']);
         return Database::executeRow($sql, $params);
     }
    //validar existencia de un producto
    public function varlidarExistencia(){
        //consulta para ver cuanto es el total de restar de existencia
        $sql = 'SELECT (cantidad_producto - ?) as resta_existencia FROM productos WHERE Id_producto = ?;';
        $params = array($this->cantidad_producto, $this->id_producto);
        return  Database::getRow($sql, $params);
    }
    //metodo para restar la cantidad que se le aumenta en el carrito 
    public function updateCantidadAumentaCarrito()/*$current_image*/
    {
         //consulta para ver cuanto es el total de aumentar la de existencia
         $sql = 'SELECT (cantidad_producto - ?) as aumentar_existencia FROM productos WHERE Id_producto = ?;';
         $params = array($this->cantidad_producto, $this->id_producto);
         //si la consulta es correcta se actualizara los datos
         if($data = Database::getRow($sql, $params)){
            $this->cantidad_producto = $data['aumentar_existencia'];
            $sql = 'UPDATE productos
                    SET cantidad_producto = ?
            WHERE id_producto = ?';
            $params = array($this->cantidad_producto,  $this->id_producto);
            if($data = Database::executeRow($sql, $params)){
                return true;
            }
            
         }
         else{
            return false;
         }
         
    }
    //metodo para restar la cantidad que se le aumenta en el carrito 
    public function updateCantidadRestaCarrito()/*$current_image*/
    {
         //consulta para ver cuanto es el total de aumentar la de existencia
         $sql = 'SELECT (cantidad_producto + ?) as aumentar_existencia FROM productos WHERE Id_producto = ?;';
         $params = array($this->cantidad_producto, $this->id_producto);
         //si la consulta es correcta se actualizara los datos
         if($data = Database::getRow($sql, $params)){
            $this->cantidad_producto = $data['aumentar_existencia'];
            $sql = 'UPDATE productos
                    SET cantidad_producto = ?
            WHERE id_producto = ?';
            $params = array($this->cantidad_producto,  $this->id_producto);
            if($data = Database::executeRow($sql, $params)){
                return true;
            }
            
         }
         else{
            return false;
         }
         
    }
    /*Metodo para agregar cosas al carrito*/
    public function createDetail()
    {
        // Se realiza una subconsulta para obtener el precio del producto.
        $sql = 'INSERT INTO detalles_pedidos(id_producto, precio_detalle_producto, cantidad_detalle_producto, id_pedido)
                VALUES(?, (SELECT precio_producto FROM productos WHERE id_producto = ?), ?, ?)';
        $params = array($this->id_producto, $this->id_producto, $this->cantidad_producto, $this->id_pedido);
        /*se verifica para actulizar la existencia de productos  en producto */
        if(Database::executeRow($sql, $params)){
            //consulta para ver cuanto es el total de restar de existencia
            $sql = 'SELECT (cantidad_producto - ?) as resta_existencia FROM productos WHERE Id_producto = ?;';
            $params = array($this->cantidad_producto, $this->id_producto);
            //se hace la actualizacion de la existencia en la tabla productos
            if($data = Database::getRow($sql, $params)){
                $this->cantidad_producto = $data['resta_existencia'];
                // se hace la resta;
                $sql = 'UPDATE productos
                SET  cantidad_producto=?
                WHERE Id_producto=?;';
                $params = array($this->cantidad_producto,$this->id_producto);
                if($data = Database::executeRow($sql, $params)){
                    return true;
                }
            }
            else{
                return false;
            }
        }
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
