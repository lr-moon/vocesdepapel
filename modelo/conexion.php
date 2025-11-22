<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "biblioteca";

// Crear conexión
$conn = new mysqli($servidor, $usuario, $password, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Opcional: Para manejar correctamente tildes y eñes
$conn->set_charset("utf8");
?>