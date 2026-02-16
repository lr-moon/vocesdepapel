<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('../controlador/conexion.php');

// Inicializar la variable de error
$error = "";

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar entradas
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    echo "Usuario ingresado: " . $username . "<br>";
    echo "Contraseña ingresada: " . $password . "<br>";
    
    // Consulta SQL para validar credenciales en la base de datos
    $sqlverificando = "SELECT * FROM login WHERE usuario = '".$username."' AND contraseña = '".$password."'";
    echo "Query: " . $sqlverificando . "<br>";
    
    $QueryResult = mysqli_query($conn, $sqlverificando);
    
    if(!$QueryResult){
        echo "Error en la consulta: " . mysqli_error($conn) . "<br>";
    }
    
    if($row = mysqli_fetch_assoc($QueryResult)){
        echo "Usuario encontrado: <br>";
        print_r($row);
        
        // Guardar datos en sesión
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['correo'] = $row['correo'];
        $_SESSION['id'] = $row['id'];
        
        echo "<br>Redirigiendo...";
        // Redirigir al panel de administración
        header("Location: admin_login.php");
        exit();
    } else {
        echo "No se encontró el usuario<br>";
        echo "Número de filas: " . mysqli_num_rows($QueryResult);
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Administrador</title>
    <link rel="icon" type="image/png" href="images/favicon.ico"> 
    <link rel="stylesheet" href="style.css/login.css"> 
</head>
<body>

    <img src="images/login.jpg" alt="Fondo Login" class="bg-image">
    <div class="bg-overlay"></div>

    <div class="login-card">
        
        <div class="login-icon">
            <svg viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
        </div>

        <h2>Acceso Admin</h2>

        <?php if (!empty($error)): ?>
            <div id="error-msg" style="display: block;"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form id="loginForm" method="POST" action="">
            
            <div class="input-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" class="input-field" placeholder="Ingresa tu usuario" 
                    required> 
            </div>

            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="input-field" placeholder="Ingresa tu contraseña" 
                    required>
            </div>

            <button type="submit" class="btn-login">Entrar</button>

        </form>

        <!-- Separador -->
        <div class="separator">
            <span>O</span>
        </div>

        <!-- Botones de redes sociales estilo Pinterest -->
        <div class="social-buttons">
            <button class="btn-social btn-facebook" type="button">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="white"/>
                </svg>
                Continue with Facebook
            </button>
            
            <button class="btn-social btn-google" type="button">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Continue with Google
            </button>

            <button class="btn-social btn-instagram" type="button">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="instagram-gradient" x1="0%" y1="100%" x2="100%" y2="0%">
                            <stop offset="0%" style="stop-color:#FED576;stop-opacity:1" />
                            <stop offset="25%" style="stop-color:#F47133;stop-opacity:1" />
                            <stop offset="50%" style="stop-color:#BC3081;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#4C63D2;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" fill="url(#instagram-gradient)"/>
                </svg>
                Continue with Instagram
            </button>
        </div>

        <a href="generos.php" class="btn-back">← Volver a la Biblioteca</a>
    </div>

</body>
</html>