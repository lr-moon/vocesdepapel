<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('../controlador/conexion.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $sqlverificando = "SELECT * FROM login WHERE usuario = '".$username."' AND contraseña = '".$password."'";
    $QueryResult = mysqli_query($conn, $sqlverificando);
    
    if($row = mysqli_fetch_assoc($QueryResult)){
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['correo'] = $row['correo'];
        $_SESSION['id'] = $row['id'];
        header("Location: admin_login.php");
        exit();
    } else {
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
    <!-- Google Identity Services -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
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
                <input type="text" id="username" name="username" class="input-field" 
                       placeholder="Ingresa tu usuario" required> 
            </div>

            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="input-field" 
                       placeholder="Ingresa tu contraseña" required>
            </div>

            <button type="submit" class="btn-login">Entrar</button>
        </form>

        <div class="separator"><span>O</span></div>

        <div class="social-buttons">

            <!-- BOTÓN DE GOOGLE (Google Identity Services) -->
            <div id="g_id_onload"
                 data-client_id="920499853013-6088qibkgup634bbfu7kl2v70o8m2a1b.apps.googleusercontent.com"
                 data-callback="handleGoogleLogin"
                 data-auto_prompt="false">
            </div>
            <div class="g_id_signin"
                 data-type="standard"
                 data-size="large"
                 data-theme="outline"
                 data-text="continue_with"
                 data-shape="rectangular"
                 data-logo_alignment="left"
                 data-width="300">
            </div>

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

    <!-- GOOGLE LOGIN CALLBACK -->
    <script>
      function handleGoogleLogin(response) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '../controlador/google_callback.php';
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'credential';
        input.value = response.credential;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
      }
    </script>

</body>
</html>