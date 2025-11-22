<?php
// Lógica de la búsqueda y ordenamiento de los libros pertenecientes al género seleccionado
include ('../controlador/conexion.php');

// Obtener el género del parámetro GET o usar uno por defecto
$genero = isset($_GET['genero']) ? $_GET['genero'] : 'Romance';

$sql = "SELECT 
        l.titulo,
        l.autor,
        l.ruta_imagen,
        l.descripcion,
        g.nombre_genero,
        lg.prioridad
    FROM libro l
    JOIN libro_genero lg ON l.id_libro = lg.id_libro
    JOIN genero g ON lg.id_genero = g.id_genero
    WHERE g.nombre_genero = ?
    ORDER BY lg.prioridad, l.titulo;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $genero);
$stmt->execute();
$result = $stmt->get_result();

$genero_principal = [];
$genero_secundario = [];
$genero_tercerio = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Clasificar los libros según su prioridad
        if ($row['prioridad'] == 1) {
            array_push($genero_principal, $row);
        } elseif ($row['prioridad'] == 2) {
            array_push($genero_secundario, $row);
        } elseif ($row['prioridad'] == 3) {
            array_push($genero_tercerio, $row);
        }
    }
} else {
    echo "No se encontraron libros para el género especificado.";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - Biblioteca Central</title>
    <link rel="stylesheet" href="style.css/select_generos.css">
</head>
<body>

    <!-- IMAGEN DE FONDO -->
    <img src="images/fondo_genero.jpg" alt="Fondo Genero" class="bg-image">
    <div class="bg-overlay"></div>

    <!-- ENCABEZADO -->
    <header>
        <div class="header-title">
            <svg style="width:28px; height:28px; fill:white;" viewBox="0 0 24 24">
                <path d="M18 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.1 2 18 2ZM6 4H11V9L8.5 7.5L6 9V4ZM18 20H6V11H18V20Z"/>
            </svg>
            Biblioteca Central
        </div>

        <!-- BOTONES -->
        <div class="header-buttons">
            <a href="index.html" class="btn-glass">
                <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                Volver
            </a>
            
            <a href="admin.html" class="btn-glass btn-admin">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                Administrador
            </a>
        </div>
    </header>

    <!-- CONTENIDO (SOLO EL TEXTO) -->
    <div class="container">
        <div class="welcome-text">
            <h1>Bienvenido a nuestra sección de <?php echo $genero; ?></h1>
        </div>
    </div>

    <!-- PRIMERA SECCIÓN - PRIORIDAD 1 -->
    <?php if (count($genero_principal) > 0): ?>
    <div class="container">
        <div class="welcome-text">
            <h3>Libros principales</h3>
        </div>
    </div>
    
    <!-- CADA SECCION DE LIBROS SE GENERA DENTRO DE UNA GRID PARA QUE TENGAN UN DISENIO ESTANDAR -->
    <div class="grid">
        <?php
        for ($i = 0; $i < count($genero_principal); $i++) {
            $parametros = "titulo=" . $genero_principal[$i]['titulo'] . 
                         "&autor=" . $genero_principal[$i]['autor'] . 
                         "&genero=" . $genero_principal[$i]['nombre_genero'] . 
                         "&descripcion=" . $genero_principal[$i]['descripcion'] . 
                         "&ruta_imagen=" . $genero_principal[$i]['ruta_imagen'];
            echo "<a href='modal.php?$parametros' class='card'>";
            echo "<div class='card-icon'>";
            echo "<img src='" . $genero_principal[$i]['ruta_imagen'] . "' alt='" . $genero_principal[$i]['titulo'] . "'>";
            echo "</div>";
            echo "<h2>" . $genero_principal[$i]['titulo'] . "</h2>";
            echo "</a>";
        }
        ?>
    </div>
    <?php endif; ?>

    <!-- SEGUNDA SECCIÓN - PRIORIDAD 2 -->
    <?php if (count($genero_secundario) > 0): ?>
    <div class="container">
        <div class="welcome-text">
            <h3>Libros secundarios</h3>
        </div>
    </div>

    <div class="grid">
        <?php
        for ($i = 0; $i < count($genero_secundario); $i++) {
            $parametros = "titulo=" . $genero_secundario[$i]['titulo'] . 
                         "&autor=" . $genero_secundario[$i]['autor'] . 
                         "&genero=" . $genero_secundario[$i]['nombre_genero'] . 
                         "&descripcion=" . $genero_secundario[$i]['descripcion'] . 
                         "&ruta_imagen=" . $genero_secundario[$i]['ruta_imagen'];
            echo "<a href='modal.php?$parametros' class='card'>";
            echo "<div class='card-icon'>";
            echo "<img src='" . $genero_secundario[$i]['ruta_imagen'] . "' alt='" . $genero_secundario[$i]['titulo'] . "'>";
            echo "</div>";
            echo "<h2>" . $genero_secundario[$i]['titulo'] . "</h2>";
            echo "</a>";
        }
        ?>
    </div>
    <?php endif; ?>

    <!-- TERCERA SECCIÓN - PRIORIDAD 3 -->
    <?php if (count($genero_tercerio) > 0): ?>
    <div class="container">
        <div class="welcome-text">
            <h3>Libros adicionales</h3>
        </div>
    </div>

    <div class="grid">
        <?php
        for ($i = 0; $i < count($genero_tercerio); $i++) {
            $parametros = "titulo=" . $genero_tercerio[$i]['titulo'] . 
                         "&autor=" . $genero_tercerio[$i]['autor'] . 
                         "&genero=" . $genero_tercerio[$i]['nombre_genero'] . 
                         "&descripcion=" . $genero_tercerio[$i]['descripcion'] . 
                         "&ruta_imagen=" . $genero_tercerio[$i]['ruta_imagen'];
            echo "<a href='modal.php?$parametros' class='card'>";
            echo "<div class='card-icon'>";
            echo "<img src='" . $genero_tercerio[$i]['ruta_imagen'] . "' alt='" . $genero_tercerio[$i]['titulo'] . "'>";
            echo "</div>";
            echo "<h2>" . $genero_tercerio[$i]['titulo'] . "</h2>";
            echo "</a>";
        }
        ?>
    </div>
    <?php endif; ?>

</body>
</html>