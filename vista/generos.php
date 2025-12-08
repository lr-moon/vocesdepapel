<?php
// Conexión a la base de datos
include ('../controlador/conexion.php');

// Consulta para obtener todos los géneros
$sql_generos = "SELECT * FROM genero ORDER BY nombre_genero";
$result_generos = $conn->query($sql_generos);

// Verificar si hay resultados
if ($result_generos->num_rows > 0) {
    $generos = array();
    while($row = $result_generos->fetch_assoc()) {
        $generos[] = $row;
    }
} else {
    $generos = array();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - Biblioteca Central</title>
    <link rel="icon" type="image/png" href="images/favicon.ico"> 
    <link rel="stylesheet" href="style.css/generos.css">
</head>
<body>

    <!-- IMAGEN DE FONDO -->
    <img src="images/hojas.jpg" alt="Fondo Hojas" class="bg-image">
    <div class="bg-overlay"></div>

    <!-- ENCABEZADO -->
    <header>
        <div class="header-title">
            <svg style="width:28px; height:28px; fill:white; margin-right:10px;" viewBox="0 0 24 24">
                <path d="M18 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.1 2 18 2ZM6 4H11V9L8.5 7.5L6 9V4ZM18 20H6V11H18V20Z"/>
            </svg>
            Biblioteca Central
        </div>

        <!-- CONTENEDOR DE BOTONES -->
        <div class="header-buttons">
            <!-- Botón Volver -->
            <a href="../index.html" class="btn-glass">
                <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                Volver
            </a>
            
            <!-- Botón Administrador -->
            <a href="login.php" class="btn-glass btn-admin">
                <!-- Icono de Usuario/Engranaje -->
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                Administrador
            </a>
        </div>
    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="container">
        
        <div class="page-intro">
            <h2>Explora por Género</h2>
            <p>Selecciona una categoría para ver los libros disponibles.</p>
        </div>

        <div class="grid">
            <?php if (count($generos) > 0): ?>
                <?php foreach ($generos as $genero): ?>
                    <!-- Tarjeta de género generada dinámicamente -->
                    <a href="select_genero.php?genero=<?php echo urlencode($genero['nombre_genero']); ?>" class="card">
                        <div class="card-icon">
                            <!-- Icono genérico de libro para todos los géneros -->
                            <svg viewBox="0 0 24 24">
                                <path d="M18 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.1 2 18 2ZM6 4H11V9L8.5 7.5L6 9V4ZM18 20H6V11H18V20Z"/>
                            </svg>
                        </div>
                        <h3><?php echo htmlspecialchars($genero['nombre_genero']); ?></h3>
                        <p><?php echo !empty($genero['descripcion']) ? htmlspecialchars($genero['descripcion']) : 'Descubre libros de este género'; ?></p>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Mensaje si no hay géneros en la base de datos -->
                <div class="no-generos">
                    <p>No hay géneros disponibles en este momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>