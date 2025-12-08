<?php
// Seleccionar todos los libros para que el administrador pueda buscarlos
include ('../controlador/conexion.php');
$sql = "SELECT DISTINCT
    libro.*
FROM
    libro
JOIN libro_genero ON libro.id_libro = libro_genero.id_libro
JOIN genero ON genero.id_genero = libro_genero.id_genero
ORDER BY
    genero.id_genero ASC;
";
$result = $conn->query($sql);
$libros = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $libros[] = $row;
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

    </style>
</head>
<link rel="stylesheet" href="style.css/admin_login.css">
<body>

    <div id="pantalla-admin">
        
        <img src="images/registro.jpg" alt="Fondo Administración" class="imagen-fondo">
        <div class="fondo-oscuro"></div>

        <div class="contenedor-principal">
            
            <div class="acciones-admin">
                <a href="agregar_genero.html" class="boton-agregar-genero">
                    <svg viewBox="0 0 24 24"><path d="M17.63 5.84C17.27 5.33 16.67 5 16 5L5 5.01C3.9 5.01 3 5.9 3 7v10c0 1.1.9 1.99 2 1.99L16 19c.67 0 1.27-.33 1.63-.84L22 12l-4.37-6.16zM16 17H5V7h11l3.55 5L16 17z"/></svg>
                    Agregar Género
                </a>

                <a href="agregar.html" class="boton-agregar-libro">
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
            
            <a href="../index.html" class="boton-cerrar-sesion">
                <svg viewBox="0 0 24 24"><path d="M10.09 15.59L11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59zM19 3H5c-1.11 0-2 .9-2 2v4h2V5h14v14H5v-4H3v4c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                Cerrar Sesión
            </a>
</br>
            <div class="cuadricula">
                <?php
                $libro['nombre_genero'] = "";
                for ($i = 0; $i < count($libros); $i++) {
                    $libro = $libros[$i];
                    $parametros_editar = "id_libro=" . $libro['id_libro'] . 
                                            "&titulo=" . $libro['titulo'] . 
                                            "&autor=" . $libro['autor'] . 
                                            "&descripcion=" . $libro['descripcion'] . 
                                            "&ruta_imagen=" . $libro['ruta_imagen'] . 
                                            "&genero=" . $libro['nombre_genero'] = "";
                    
                    echo "<div class='tarjeta' 
                            data-titulo='" . $libro['titulo'] . "' 
                            data-autor='" . $libro['autor'] . "' 
                            data-genero='" . $libro['nombre_genero'] = "" . "' 
                            data-descripcion='" . $libro['descripcion'] . "' 
                            data-imagen='" . $libro['ruta_imagen'] . "'>";
                    echo "<div class='icono-tarjeta'>";
                    echo "<img src='" . $libro['ruta_imagen'] . "' alt='" . $libro['titulo'] . "'>";
                    echo "</div>";
                    echo "<h2>" . $libro['titulo'] . "</h2>";
                    echo "<div class='botones-accion'>";
                    echo "<a href='editar_libro.php?" . $parametros_editar . "' class='boton-editar'>Editar</a>";
                    echo "<a href='../modelo/eliminar_libro.php?id=" . $libro['id_libro'] . "' class='boton-eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este libro?\");'>Eliminar</a>";
                    echo "</div>";
                    echo "</div>";
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