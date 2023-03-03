<?php
require 'datosConexion.php';
session_start();
    $usuario = $_POST['user'];
    $clave=$_POST['pass'];

    $query = "SELECT * FROM usuarios WHERE nombre='$usuario' AND contrasena ='$clave'";
    $consulta = pg_query($conexion,$query);
    $cantidad = pg_num_rows($consulta);
    if ($cantidad>0){
        $_SESSION['nombre']=$usuario;
        header("location: http://localhost/Proyect-Music-Fusion-2023/views/dashboard/dashboard.html");
    }else{

        echo "Datos incorrectos";
    }
?>