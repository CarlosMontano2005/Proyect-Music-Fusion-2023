<?php
define('SERVER', 'localhost');
define('DATABASE', 'DB_prueba');
define('USERNAME', 'postgres');
define('PASSWORD', '123');

$conexion = pg_connect("host=localhost dbname=DB_prueba user=postgres password=123");
?>