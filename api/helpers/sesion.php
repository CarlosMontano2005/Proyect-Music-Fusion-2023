<?php

require 'config.php';

session_start();

$usuario=$_POST['user'];
$clave=$_POST['pass'];

$query=("SELECT * FROM usuarios 
	WHERE nombre_usuario='$usuario' AND clave_usuario='$clave'");

$consulta=pg_query($conexion,$query);
$cantidad=pg_num_rows($consulta);

if($cantidad>0){

	$_SESSION['nombre_usuario']=$usuario;
	header('Location:home.html');
}
else{
	echo "Datos incorrectos.";
}




?>