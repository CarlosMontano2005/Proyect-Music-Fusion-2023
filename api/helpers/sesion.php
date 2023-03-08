<?php
require 'datosConexion.php';
session_start();
    $usuario = $_POST['user'];
    $clave=$_POST['pass'];

    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    $query = "SELECT * FROM usuarios WHERE correo_usuario='$usuario' AND clave_usuario ='$clave'";
    $consulta = pg_query($conexion,$query);
    $cantidad = pg_num_rows($consulta);
    if ($cantidad>0){
        $_SESSION['correo_usuario']=$usuario;
        $result['status'] = 1;
        $result['message'] =  'Autenticación correcta';
    }else{

        $result['exception'] = 'Datos incorrectos';
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
?>