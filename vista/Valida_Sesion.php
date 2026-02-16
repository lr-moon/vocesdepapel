<?php 
error_reporting(0);
session_start();
include('conexion.php');

// Sanitizar el correo
$correo = filter_var($_REQUEST['email'], FILTER_SANITIZE_EMAIL);
if(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
    echo '<meta http-equiv="refresh" content="0; url=../vista/login.php">';
    exit();
}

$clave = trim($_REQUEST['password']);

// Consulta SQL 
$sqlverificando = "SELECT * FROM login WHERE correo = '".$correo."' AND contraseña = '".$clave."'";
$QueryResult = mysqli_query($conn, $sqlverificando);

if($row = mysqli_fetch_assoc($QueryResult)){
    // Guardar datos en sesión
    $_SESSION['id'] = $row['id'];
    $_SESSION['nombre'] = $row['nombre'];
    $_SESSION['usuario'] = $row['usuario'];
    $_SESSION['correo'] = $row['correo'];

    // Redirigir a admin
    echo '<meta http-equiv="refresh" content="0; url=../vista/admin_login.php">';
} else {
    // Credenciales incorrectas
    echo '<meta http-equiv="refresh" content="0; url=../vista/login.php">';
}
?>