<?php
//en este script se conecta a una bd mysql.
//Para mirar el servidor, usuario y contraseña revisar Apache-config: phpMyAdmin(config.inc.php)
//PDO::__construct — Crea una instancia de PDO que representa una conexión a una base de datos
//$servidor = "localhost:3307";
$dsn = 'mysql:dbname=sitio;host=localhost:3307';
$usuario = "root";
$contrasenia = "";

try{
    $conexion = new PDO($dsn,$usuario,$contrasenia);
    //echo "Conexión extablecida";
}catch(PDOException $e){
    //PDO::__construct() lanza una PDOException si el intento de conexión a la base de datos requerida falla.
    echo "Conexión erronea".$e;
}
?>