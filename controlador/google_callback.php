<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('conexion.php');

if (!isset($_POST['credential']) || empty($_POST['credential'])) {
    header("Location: ../vista/login.php?error=credencial_invalida");
    exit();
}

$credential = $_POST['credential'];

// Decodificar el JWT que envía Google (sin librería externa)
$parts = explode('.', $credential);

if (count($parts) !== 3) {
    header("Location: ../vista/login.php?error=token_invalido");
    exit();
}

$payload = json_decode(
    base64_decode(
        str_pad(
            strtr($parts[1], '-_', '+/'),
            strlen($parts[1]) % 4,
            '=',
            STR_PAD_RIGHT
        )
    ),
    true
);

if (!$payload || !isset($payload['email'])) {
    header("Location: ../vista/login.php?error=datos_invalidos");
    exit();
}

$nombre    = isset($payload['name'])  ? mysqli_real_escape_string($conn, $payload['name'])  : 'Usuario Google';
$email     = mysqli_real_escape_string($conn, $payload['email']);
$google_id = isset($payload['sub'])   ? mysqli_real_escape_string($conn, $payload['sub'])   : '';

// Verificar si el usuario ya existe por correo
$sql    = "SELECT * FROM login WHERE correo = '$email'";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    // Usuario ya existe — iniciar sesión directamente
    $_SESSION['nombre']  = $row['nombre'];
    $_SESSION['usuario'] = $row['usuario'];
    $_SESSION['correo']  = $row['correo'];
    $_SESSION['id']      = $row['id'];
} else {
    // Usuario nuevo — registrar automáticamente
    $usuario_generado = strtolower(str_replace(' ', '_', $nombre)) . '_' . substr($google_id, 0, 5);
    $sql_insert = "INSERT INTO login (nombre, correo, usuario, contraseña) 
                   VALUES ('$nombre', '$email', '$usuario_generado', 'google_oauth')";
    
    if (mysqli_query($conn, $sql_insert)) {
        $nuevo_id = mysqli_insert_id($conn);
        $_SESSION['nombre']  = $nombre;
        $_SESSION['usuario'] = $usuario_generado;
        $_SESSION['correo']  = $email;
        $_SESSION['id']      = $nuevo_id;
    } else {
        header("Location: ../vista/login.php?error=registro_fallido");
        exit();
    }
}

header("Location: ../vista/admin_login.php");
exit();
?>