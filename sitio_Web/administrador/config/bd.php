<?php
$host = 'localhost:3307';
$usuario = "root";
$contrasenia = "";
$bd="sitio";

try{
    $conexion = new PDO("mysql:host=$host;dbname=$bd",$usuario,$contrasenia);
    //if($conexion){echo "conectado";}
    //echo "Conexión extablecida";
}catch(PDOException $ex){
    //PDO::__construct() lanza una PDOException si el intento de conexión a la base de datos requerida falla.
    echo "Conexión erronea".$e;
}
?>