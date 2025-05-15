<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "judomex";

$conexion = new mysqli($server, $user, $pass, $db);

if($conexion->connect_errno){
    die("Conexión Fallida" . $conexion->connect_errno);
} else {
    echo"Conectado";
}

?>