<?php
// Seleccionar todos los libros con su género principal (prioridad 1)
include ('../controlador/conexion.php');

$sql = "SELECT 
            l.*,
            g.nombre_genero as genero_principal,
            g.id_genero as id_genero_principal
        FROM libro l
        JOIN libro_genero lg ON l.id_libro = lg.id_libro
        JOIN genero g ON lg.id_genero = g.id_genero
        WHERE lg.prioridad = 1
        ORDER BY g.nombre_genero ASC, l.titulo ASC";

$result = $conn->query($sql);
$libros = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $libros[] = $row;
    }
}

// Obtener todos los géneros para el filtro (opcional)
$sql_generos = "SELECT * FROM genero ORDER BY nombre_genero";
$result_generos = $conn->query($sql_generos);
$generos = [];
if ($result_generos->num_rows > 0) {
    while($row = $result_generos->fetch_assoc()) {
        $generos[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/favicon.ico"> 
    <title>Panel de Administración - Biblioteca</title>
    <style>
        .filtro-genero {
            margin: 20px 0;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        
        .filtro-genero select {
            padding: 8px 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background: white;
            font-size: 16px;
            margin-left: 10px;
        }
        
        .etiqueta-genero {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>
<link rel="stylesheet" href="style.css/admin_login.css">
<body>

    <div id="pantalla-admin">
        
        <img src="images/registro.jpg" alt="Fondo Administración" class="imagen-fondo">
        <div class="fondo-oscuro"></div>

        <div class="contenedor-principal">
            
            <div class="acciones-admin">
                <a href="agregar_genero.php" class="boton-agregar-genero">
                    <svg viewBox="0 0 24 24"><path d="M17.63 5.84C17.27 5.33 16.67 5 16 5L5 5.01C3.9 5.01 3 5.9 3 7v10c0 1.1.9 1.99 2 1.99L16 19c.67 0 1.27-.33 1.63-.84L22 12l-4.37-6.16zM16 17H5V7h11l3.55 5L16 17z"/></svg>
                    Agregar Género
                </a>

                <a href="agregar.php" class="boton-agregar-libro">
                    <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                    Agregar Nuevo Libro 
                </a>
            </div>
            
            <div class="icono-grande">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.1 2 18 2ZM6 4H11V9L8.5 7.5L6 9V4ZM18 20H6V11H18V20Z"/>
                </svg>
            </div>
            
            <h1>Administración</h1>
            <p class="subtitulo">Gestiona el catálogo y los recursos de la biblioteca.</p>
            
            <!-- Filtro por género -->
            <div class="filtro-genero">
                <label for="select-genero">Filtrar por género:</label>
                <select id="select-genero" onchange="filtrarPorGenero()">
                    <option value="">Todos los géneros</option>
                    <?php foreach($generos as $genero): ?>
                        <option value="<?php echo $genero['id_genero']; ?>">
                            <?php echo htmlspecialchars($genero['nombre_genero']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <a href="../index.html" class="boton-cerrar-sesion">
                <svg viewBox="0 0 24 24"><path d="M10.09 15.59L11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59zM19 3H5c-1.11 0-2 .9-2 2v4h2V5h14v14H5v-4H3v4c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                Cerrar Sesión
            </a>
            </br>

            <div class="cuadricula">
                <?php
                if (count($libros) > 0) {
                    foreach ($libros as $libro) {
                        // Solo pasamos el id_libro para editar
                        $id_libro = $libro['id_libro'];
                        $genero_principal = isset($libro['genero_principal']) ? $libro['genero_principal'] : '';
                        $id_genero_principal = isset($libro['id_genero_principal']) ? $libro['id_genero_principal'] : '';
                        
                        echo "<div class='tarjeta' 
                                data-titulo='" . htmlspecialchars($libro['titulo']) . "' 
                                data-autor='" . htmlspecialchars($libro['autor']) . "' 
                                data-genero='" . htmlspecialchars($genero_principal) . "'
                                data-id-genero='" . $id_genero_principal . "'
                                data-descripcion='" . htmlspecialchars($libro['descripcion']) . "' 
                                data-imagen='" . htmlspecialchars($libro['ruta_imagen']) . "'>";
                        
                        echo "<div class='icono-tarjeta'>";
                        // Imagen del libro con manejo de error
                        echo "<img src='" . htmlspecialchars($libro['ruta_imagen']) . "' 
                                   alt='" . htmlspecialchars($libro['titulo']) . "'
                                   onerror=\"this.src='images/no_found.png';\">";
                        echo "</div>";
                        
                        echo "<h2>" . htmlspecialchars($libro['titulo']) . "</h2>";
                        
                        // Mostrar el género principal
                        if ($genero_principal) {
                            echo "<div class='etiqueta-genero'>" . htmlspecialchars($genero_principal) . "</div>";
                        }
                        
                        echo "<div class='botones-accion'>";
                        // Enlace de editar - solo pasa el id_libro
                        echo "<a href='editar_libro.php?id_libro=" . $id_libro . "' class='boton-editar'>Editar</a>";
                        echo "<a href='../modelo/eliminar_libro.php?id=" . $id_libro . "' 
                               class='boton-eliminar' 
                               onclick='return confirm(\"¿Estás seguro de que deseas eliminar este libro?\");'>Eliminar</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p style='color: white; text-align: center; width: 100%;'>No hay libros en la base de datos.</p>";
                }
                ?>
            </div>

        </div>
    </div>

    <?php include 'modal.php'; ?>
    
    <script>
        function abrirModalDesdeCard(elemento) {
            const titulo = elemento.getAttribute('data-titulo');
            const autor = elemento.getAttribute('data-autor');
            const genero = elemento.getAttribute('data-genero');
            const descripcion = elemento.getAttribute('data-descripcion');
            const imagen = elemento.getAttribute('data-imagen');
            
            abrirModal(titulo, autor, genero, descripcion, imagen);
        }
        
        // Función para filtrar libros por género
        function filtrarPorGenero() {
            const select = document.getElementById('select-genero');
            const generoId = select.value;
            const tarjetas = document.querySelectorAll('.tarjeta');
            
            tarjetas.forEach(tarjeta => {
                const tarjetaGeneroId = tarjeta.getAttribute('data-id-genero');
                
                if (!generoId || generoId === tarjetaGeneroId) {
                    tarjeta.style.display = 'block';
                } else {
                    tarjeta.style.display = 'none';
                }
            });
        }
        
        // Asignar eventos a todas las tarjetas
        document.addEventListener('DOMContentLoaded', function() {
            const tarjetas = document.querySelectorAll('.tarjeta');
            tarjetas.forEach(function(tarjeta) {
                // Solo abrir modal al hacer click en la imagen o título, no en los botones
                const icono = tarjeta.querySelector('.icono-tarjeta');
                const titulo = tarjeta.querySelector('h2');
                
                if (icono) {
                    icono.addEventListener('click', function() {
                        abrirModalDesdeCard(tarjeta);
                    });
                }
                
                if (titulo) {
                    titulo.addEventListener('click', function() {
                        abrirModalDesdeCard(tarjeta);
                    });
                }
            });
        });
    </script>
</body>
</html>