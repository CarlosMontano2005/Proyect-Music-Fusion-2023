<?php
define('SERVER', 'localhost');
define('DATABASE', 'Music_Fusion');
define('USERNAME', 'postgres');
define('PASSWORD', '123');

$conexion = pg_connect("host=localhost dbname=Music_Fusion user=postgres password=123");
?>