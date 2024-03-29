<?php
require_once('../../helpers/database.php');
/*
*	Clase para manejar el acceso a datos de la entidad PRODUCTO.
*/
class ClienteQueries
{
    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    
    public function searchRows($value)
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, correo_cliente, fecha_nacimiento, dui, genero, telefono_cliente, estado, direccion_cliente
        FROM clientes INNER JOIN generos USING(id_genero) 
                WHERE nombre_cliente ILIKE ? OR apellido_cliente ILIKE ? OR correo_cliente ILIKE ? OR dui ILIKE ?
                ORDER BY nombre_cliente';
        $params = array("%$value%", "%$value%","%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, correo_cliente, fecha_nacimiento, genero, telefono_cliente,dui,  estado, direccion_cliente
        FROM clientes INNER JOIN generos USING(id_genero) ORDER BY  (id_cliente) asc ';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, correo_cliente, fecha_nacimiento, dui, genero, telefono_cliente, estado, direccion_cliente
        FROM clientes INNER JOIN generos USING(id_genero) 
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
    public function readAllGenero()
    {
        $sql = 'SELECT id_genero, genero
        FROM generos';
        return Database::getRows($sql);
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
    public function createRowCrearCuenta()
    {
        $sql = 'INSERT INTO clientes(nombre_cliente, apellido_cliente, correo_cliente, 
        fecha_nacimiento, dui, id_genero, telefono_cliente, clave,direccion_cliente)
VALUES (?,?,?,?,?, ?, ?,?, ?)';
        $params = array($this->nombre_cliente, $this->apellido_cliente, $this->correo_cliente, $this->fecha_nacimiento, $this->dui_cliente, $this->id_genero, $this->telefono_cliente, $this->clave, $this->direccion_cliente);
        return Database::executeRow($sql, $params);
    }

    public function updateRow()/*$current_image*/
    {
        // Se verifica si existe una nueva imagen para borrar la actual, de lo contrario se mantiene la actual.
        /*($this->imagen) ? Validator::deleteFile($this->getRuta(), $current_image) : $this->imagen = $current_image;*/

        $sql = 'UPDATE clientes
        SET  nombre_cliente=?, apellido_cliente=?, correo_cliente=?, fecha_nacimiento=?, dui=?, id_genero=?, telefono_cliente=?, clave=?, estado=?, direccion_cliente=?
        WHERE id_cliente=?';
        $params = array($this->nombre_cliente, $this->apellido_cliente, $this->correo_cliente, $this->fecha_nacimiento, $this->dui_cliente, $this->id_genero, $this->telefono_cliente, $this->clave,$this->estado,$this->direccion_cliente, $this->id);
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
    /**
     * 
     * Metodo del login
     */

     public function checkUser($correo_cliente)
    {
        $sql = 'SELECT id_cliente, estado FROM clientes WHERE correo_cliente = ?';
        $params = array($correo_cliente);
        if ($data = Database::getRow($sql, $params)) {
            $this->id = $data['id_cliente'];
            $this->estado = $data['estado'];
            $this->correo_cliente = $correo_cliente;
            return true;
        } else {
            return false;
        }
    }
    public function dataUser()
    {
        $sql = 'SELECT * FROM clientes';
        return Database::getRows($sql);
    }

    public function checkPassword($password)
    {
        $sql = 'SELECT clave FROM clientes WHERE id_cliente = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        //if ($password == $data['clave_usuario']) {
        if (password_verify($password , $data['clave'])) {
            return true;
        } else {
            return false;
        }
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
     /**
     * grafica
     */
    public function porcentajeClienteGeneros()
    {
        $sql = 'SELECT generos.genero, ROUND(
            (COUNT(id_cliente) * 100.0 / (SELECT COUNT(id_cliente) FROM clientes)), 2
                                    ) porcentaje
                        FROM clientes
						INNER JOIN generos USING(id_genero)
                        GROUP BY genero ORDER BY porcentaje  DESC';
        return Database::getRows($sql);
    }
}
