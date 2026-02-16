<?php
session_start();

// Eliminar variables de sesión
unset($_SESSION['id']);
unset($_SESSION['nombre']);
unset($_SESSION['usuario']);
unset($_SESSION['correo']);

// Destruir la sesión
session_destroy();

// Eliminar la cookie de sesión
$parametros_cookies = session_get_cookie_params();
setcookie(session_name(), '', time() - 3600, $parametros_cookies["path"]);

// Redirigir al login
header("Location: login.php");
exit();
?>