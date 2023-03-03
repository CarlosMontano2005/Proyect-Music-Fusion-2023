<?php
include('datosConexion.php');
class Conexion{
  function Conectar(){

    try{
      $conexion = new PDO("pgsql:host=".SERVER.";port=5432;dbname=".DATABASE, USERNAME, PASSWORD);
      return $conexion;

    }catch(Exception $error){
      die("El error de conexion es: ".$error->getMessage());
    }
  }

}

?>
