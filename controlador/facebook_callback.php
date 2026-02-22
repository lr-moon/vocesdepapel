<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('conexion.php');

// Recibir datos enviados desde login.php via JavaScript
$nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$email  = isset($_GET['email'])  ? trim($_GET['email'])  : '';
$token  = isset($_GET['token'])  ? trim($_GET['token'])  : '';

// Verificar que llegaron los datos
if (empty($nombre) || empty($token)) {
    die("Error: No se recibieron datos de Facebook.");
}

// Buscar si el usuario ya existe en la base de datos por su email
$email_seguro = mysqli_real_escape_string($conn, $email);
$nombre_seguro = mysqli_real_escape_string($conn, $nombre);

$sql_buscar = "SELECT * FROM login WHERE correo = '$email_seguro'";
$resultado  = mysqli_query($conn, $sql_buscar);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    // ✅ El usuario ya existe → iniciar sesión directamente
    $row = mysqli_fetch_assoc($resultado);

    $_SESSION['nombre']  = $row['nombre'];
    $_SESSION['usuario'] = $row['usuario'];
    $_SESSION['correo']  = $row['correo'];
    $_SESSION['id']      = $row['id'];

    // Redirigir al panel de administración
    header("Location: ../vista/admin_login.php");
    exit();

} else {
    // ⚠️ El usuario NO existe → registrarlo automáticamente
    // Usamos el email como usuario y generamos una contraseña aleatoria
    $usuario_fb   = strtolower(str_replace(' ', '_', $nombre_seguro));
    $password_fb  = bin2hex(random_bytes(8)); // contraseña aleatoria (no la usará, entra por Facebook)

    $sql_insertar = "INSERT INTO login (nombre, usuario, correo, contraseña) 
                     VALUES ('$nombre_seguro', '$usuario_fb', '$email_seguro', '$password_fb')";

    if (mysqli_query($conn, $sql_insertar)) {
        // Registro exitoso → iniciar sesión
        $_SESSION['nombre']  = $nombre;
        $_SESSION['usuario'] = $usuario_fb;
        $_SESSION['correo']  = $email;
        $_SESSION['id']      = mysqli_insert_id($conn);

        header("Location: ../vista/admin_login.php");
        exit();
    } else {
        die("Error al registrar usuario: " . mysqli_error($conn));
    }
}
?>