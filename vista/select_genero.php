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
        // IMPORTANTE: Limpiar saltos de línea para que no rompan el JavaScript del onclick
        $row['descripcion'] = str_replace(["\r", "\n"], ' ', $row['descripcion']);
        
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
    <title>Libros de <?php echo htmlspecialchars($genero); ?> - Biblioteca Central</title>
    
    <!-- ESTILOS INTEGRADOS PARA EL DISEÑO -->
    <style>
        body { margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; color: white; min-height: 100vh; background-color: #1a1a1a; }
        .bg-image { position: fixed; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: -2; }
        .bg-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); z-index: -1; }

        /* HEADER */
        header { padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; background: linear-gradient(to bottom, rgba(0,0,0,0.8), transparent); }
        .header-title { font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 10px; text-shadow: 0 2px 4px rgba(0,0,0,0.5); }
        .header-buttons { display: flex; gap: 10px; }
        
        .btn-glass { text-decoration: none; color: white; font-weight: 600; display: flex; align-items: center; padding: 8px 16px; border-radius: 50px; transition: 0.3s; background-color: rgba(255,255,255,0.15); backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.2); }
        .btn-glass:hover { background-color: #e67e22; border-color: #e67e22; transform: translateY(-2px); }
        .btn-glass svg { width: 18px; height: 18px; margin-right: 8px; fill: white; }

        /* CONTENEDORES */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .welcome-text { text-align: center; margin: 40px 0 20px 0; }
        .welcome-text h1 { font-size: 3rem; margin: 0; text-shadow: 0 4px 8px rgba(0,0,0,0.6); }
        .welcome-text h3 { font-size: 1.8rem; margin: 40px 0 20px 0; border-bottom: 2px solid rgba(255,255,255,0.2); padding-bottom: 10px; display: inline-block; text-shadow: 0 2px 4px rgba(0,0,0,0.5); }

        /* GRID */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 30px; padding-bottom: 60px; }

        /* TARJETA (DIV no A) */
        .card {
            background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;
            color: white; cursor: pointer; /* Importante: cursor de mano */
            display: flex; flex-direction: column; align-items: center;
            transition: 0.3s; height: 100%; box-sizing: border-box;
        }
        .card:hover { transform: translateY(-10px); background-color: rgba(255,255,255,0.2); border-color: #e67e22; box-shadow: 0 10px 20px rgba(0,0,0,0.3); }
        
        .card-icon { width: 100%; aspect-ratio: 2/3; overflow: hidden; border-radius: 10px; margin-bottom: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .card-icon img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .card:hover .card-icon img { transform: scale(1.05); }
        
        .card h2 { font-size: 1.1rem; margin: 0; text-align: center; font-weight: 600; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .card-author { font-size: 0.9rem; color: #ddd; margin-top: 5px; font-style: italic; }
    </style>
</head>
<body>

    <!-- IMAGEN DE FONDO -->
    <img src="images/fondo_select.jpg" alt="Fondo Genero" class="bg-image">
    <div class="bg-overlay"></div>

    <!-- ENCABEZADO -->
    <header>
        <div class="header-title">
            <svg style="width:28px; height:28px; fill:white;" viewBox="0 0 24 24"><path d="M18 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.1 2 18 2ZM6 4H11V9L8.5 7.5L6 9V4ZM18 20H6V11H18V20Z"/></svg>
            Biblioteca Central
        </div>

        <!-- BOTONES -->
        <div class="header-buttons">
            <a href="generos.html" class="btn-glass">
                <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg> Volver
            </a>
            <a href="login.html" class="btn-glass btn-admin">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg> Administrador
            </a>
        </div>
    </header>

    <!-- CONTENIDO -->
    <div class="container">
        <div class="welcome-text">
            <h1>Bienvenido a nuestra sección de <?php echo htmlspecialchars($genero); ?></h1>
        </div>
    </div>

    <!-- PRIMERA SECCIÓN - PRIORIDAD 1 -->
    <?php if (count($genero_principal) > 0): ?>
    <div class="container">
        <div class="welcome-text"><h3>Libros principales</h3></div>
    </div>
    
    <div class="grid">
        <?php
        for ($i = 0; $i < count($genero_principal); $i++) {
            // Preparamos los datos para pasarlos a la función JS (escapando comillas)
            $titulo = addslashes($genero_principal[$i]['titulo']);
            $autor = addslashes($genero_principal[$i]['autor']);
            $gen = addslashes($genero_principal[$i]['nombre_genero']);
            $desc = addslashes($genero_principal[$i]['descripcion']);
            $img = addslashes($genero_principal[$i]['ruta_imagen']);

            // CAMBIO: Usamos DIV con onclick, NO etiqueta "a"
            echo "<div class='card' onclick=\"abrirModal('$titulo', '$autor', '$gen', '$desc', '$img')\">";
            echo "<div class='card-icon'>";
            echo "<img src='" . $genero_principal[$i]['ruta_imagen'] . "' alt='" . $genero_principal[$i]['titulo'] . "'>";
            echo "</div>";
            echo "<h2>" . $genero_principal[$i]['titulo'] . "</h2>";
            echo "<span class='card-author'>" . $genero_principal[$i]['autor'] . "</span>";
            echo "</div>";
        }
        ?>
    </div>
    <?php endif; ?>

    <!-- SEGUNDA SECCIÓN - PRIORIDAD 2 -->
    <?php if (count($genero_secundario) > 0): ?>
    <div class="container">
        <div class="welcome-text"><h3>Libros secundarios</h3></div>
    </div>

    <div class="grid">
        <?php
        for ($i = 0; $i < count($genero_secundario); $i++) {
            $titulo = addslashes($genero_secundario[$i]['titulo']);
            $autor = addslashes($genero_secundario[$i]['autor']);
            $gen = addslashes($genero_secundario[$i]['nombre_genero']);
            $desc = addslashes($genero_secundario[$i]['descripcion']);
            $img = addslashes($genero_secundario[$i]['ruta_imagen']);

            echo "<div class='card' onclick=\"abrirModal('$titulo', '$autor', '$gen', '$desc', '$img')\">";
            echo "<div class='card-icon'>";
            echo "<img src='" . $genero_secundario[$i]['ruta_imagen'] . "' alt='" . $genero_secundario[$i]['titulo'] . "'>";
            echo "</div>";
            echo "<h2>" . $genero_secundario[$i]['titulo'] . "</h2>";
            echo "<span class='card-author'>" . $genero_secundario[$i]['autor'] . "</span>";
            echo "</div>";
        }
        ?>
    </div>
    <?php endif; ?>

    <!-- TERCERA SECCIÓN - PRIORIDAD 3 -->
    <?php if (count($genero_tercerio) > 0): ?>
    <div class="container">
        <div class="welcome-text"><h3>Libros adicionales</h3></div>
    </div>

    <div class="grid">
        <?php
        for ($i = 0; $i < count($genero_tercerio); $i++) {
            $titulo = addslashes($genero_tercerio[$i]['titulo']);
            $autor = addslashes($genero_tercerio[$i]['autor']);
            $gen = addslashes($genero_tercerio[$i]['nombre_genero']);
            $desc = addslashes($genero_tercerio[$i]['descripcion']);
            $img = addslashes($genero_tercerio[$i]['ruta_imagen']);

            echo "<div class='card' onclick=\"abrirModal('$titulo', '$autor', '$gen', '$desc', '$img')\">";
            echo "<div class='card-icon'>";
            echo "<img src='" . $genero_tercerio[$i]['ruta_imagen'] . "' alt='" . $genero_tercerio[$i]['titulo'] . "'>";
            echo "</div>";
            echo "<h2>" . $genero_tercerio[$i]['titulo'] . "</h2>";
            echo "<span class='card-author'>" . $genero_tercerio[$i]['autor'] . "</span>";
            echo "</div>";
        }
        ?>
    </div>
    <?php endif; ?>

    <!-- INCLUIR LA MODAL -->
    <?php include 'modal.php'; ?>

</body>
</html>