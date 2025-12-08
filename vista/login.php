<?php

$USUARIO_CORRECTO = "admin";
$PASSWORD = "1234";

// Inicializar la variable de error
$error = "";

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // No usamos trim() ni htmlspecialchars() para simplificar
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Validar credenciales
    if ($username === $USUARIO_CORRECTO && $password === $PASSWORD) {
        // Redirigir al panel de administración  'admin_login.php'
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

        <a href="generos.php" class="btn-back">← Volver a la Biblioteca</a>
    </div>

</body>
</html>