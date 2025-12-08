<?php
// Lógica de la búsqueda y ordenamiento de los libros pertenecientes al género seleccionado
include ('../controlador/conexion.php');

// Obtener el género del parámetro GET o usar uno por defecto
$genero = 'Romance';
if (isset($_GET['genero'])) {
    $genero = $_GET['genero'];
}

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
} 

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libros de <?php echo $genero; ?> - Biblioteca Central</title>
    <link rel="icon" type="image/png" href="images/favicon.ico"> 
    <link rel="stylesheet" href="style.css/select_generos.css">
</head>
<body>

    <img src="images/fondo_select.jpg" alt="Fondo Genero" class="imagen-fondo">
    <div class="fondo-oscuro"></div>

    <header>
        <div class="titulo-encabezado">
            <svg style="width:28px; height:28px; fill:white;" viewBox="0 0 24 24"><path d="M18 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.1 2 18 2ZM6 4H11V9L8.5 7.5L6 9V4ZM18 20H6V11H18V20Z"/></svg>
            Biblioteca Central
        </div>

        <div class="botones-encabezado">
            <a href="generos.php" class="boton-vidrio">
                <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg> Volver
            </a>
            <a href="login.php" class="boton-vidrio boton-administrador">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg> Administrador
            </a>
        </div>
    </header>

    <div class="contenedor">
        <div class="texto-bienvenida">
            <h1>Bienvenido a nuestra sección de <?php echo $genero; ?></h1>
        </div>
    </div>

    <!-- PRIMERA SECCIÓN - PRIORIDAD 1 -->
    <?php if (count($genero_principal) > 0): ?>
    <div class="contenedor">
        <div class="texto-bienvenida"><h3>Libros principales</h3></div>
    </div>
    
    <div class="cuadricula">
        <?php
        for ($i = 0; $i < count($genero_principal); $i++) {
            echo "<div class='tarjeta' 
                    data-titulo='" . $genero_principal[$i]['titulo'] . "' 
                    data-autor='" . $genero_principal[$i]['autor'] . "' 
                    data-genero='" . $genero_principal[$i]['nombre_genero'] . "' 
                    data-descripcion='" . $genero_principal[$i]['descripcion'] . "' 
                    data-imagen='" . $genero_principal[$i]['ruta_imagen'] . "'>";
            echo "<div class='icono-tarjeta'>";
            echo "<img src='" . $genero_principal[$i]['ruta_imagen'] . "' alt='" . $genero_principal[$i]['titulo'] . "'>";
            echo "</div>";
            echo "<h2>" . $genero_principal[$i]['titulo'] . "</h2>";
            echo "<span class='autor-tarjeta'>" . $genero_principal[$i]['autor'] . "</span>";
            echo "</div>";
        }
        ?>
    </div>
    <?php endif; ?>

    <!-- SEGUNDA SECCIÓN - PRIORIDAD 2 -->
    <?php if (count($genero_secundario) > 0): ?>
    <div class="contenedor">
        <div class="texto-bienvenida"><h3>Libros secundarios</h3></div>
    </div>

    <div class="cuadricula">
        <?php
        for ($i = 0; $i < count($genero_secundario); $i++) {
            echo "<div class='tarjeta' 
                    data-titulo='" . $genero_secundario[$i]['titulo'] . "' 
                    data-autor='" . $genero_secundario[$i]['autor'] . "' 
                    data-genero='" . $genero_secundario[$i]['nombre_genero'] . "' 
                    data-descripcion='" . $genero_secundario[$i]['descripcion'] . "' 
                    data-imagen='" . $genero_secundario[$i]['ruta_imagen'] . "'>";
            echo "<div class='icono-tarjeta'>";
            echo "<img src='" . $genero_secundario[$i]['ruta_imagen'] . "' alt='" . $genero_secundario[$i]['titulo'] . "'>";
            echo "</div>";
            echo "<h2>" . $genero_secundario[$i]['titulo'] . "</h2>";
            echo "<span class='autor-tarjeta'>" . $genero_secundario[$i]['autor'] . "</span>";
            echo "</div>";
        }
        ?>
    </div>
    <?php endif; ?>

    <!-- TERCERA SECCIÓN - PRIORIDAD 3 -->
    <?php if (count($genero_tercerio) > 0): ?>
    <div class="contenedor">
        <div class="texto-bienvenida"><h3>Libros adicionales</h3></div>
    </div>

    <div class="cuadricula">
        <?php
        for ($i = 0; $i < count($genero_tercerio); $i++) {
            echo "<div class='tarjeta' 
                    data-titulo='" . $genero_tercerio[$i]['titulo'] . "' 
                    data-autor='" . $genero_tercerio[$i]['autor'] . "' 
                    data-genero='" . $genero_tercerio[$i]['nombre_genero'] . "' 
                    data-descripcion='" . $genero_tercerio[$i]['descripcion'] . "' 
                    data-imagen='" . $genero_tercerio[$i]['ruta_imagen'] . "'>";
            echo "<div class='icono-tarjeta'>";
            echo "<img src='" . $genero_tercerio[$i]['ruta_imagen'] . "' alt='" . $genero_tercerio[$i]['titulo'] . "'>";
            echo "</div>";
            echo "<h2>" . $genero_tercerio[$i]['titulo'] . "</h2>";
            echo "<span class='autor-tarjeta'>" . $genero_tercerio[$i]['autor'] . "</span>";
            echo "</div>";
        }
        ?>
    </div>
    <?php endif; ?>
    <?php include 'modal.php'; ?>
    
    <!-- JavaScript para abrir el modal -->
    <script>
        function abrirModalDesdeCard(elemento) {
            const titulo = elemento.getAttribute('data-titulo');
            const autor = elemento.getAttribute('data-autor');
            const genero = elemento.getAttribute('data-genero');
            const descripcion = elemento.getAttribute('data-descripcion');
            const imagen = elemento.getAttribute('data-imagen');
            
            // Llamar a la función del modal con los datos
            abrirModal(titulo, autor, genero, descripcion, imagen);
        }
        
        // Asignar eventos a todas las tarjetas
        document.addEventListener('DOMContentLoaded', function() {
            const tarjetas = document.querySelectorAll('.tarjeta');
            tarjetas.forEach(function(tarjeta) {
                tarjeta.addEventListener('click', function() {
                    abrirModalDesdeCard(this);
                });
            });
        });
    </script>
</body>
</html>