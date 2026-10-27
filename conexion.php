<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "conexiónbd";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("❌ Error de conexión: " . $conexion->connect_error);
}
?>