<?php
session_start();
if(!isset($_SESSION['id']) || !isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}
?>
<?php
// Seleccionar todos los libros con su género principal (prioridad 1
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
        /* Estilos generales */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            min-height: 100vh;
            background-color: #1a1a1a;
        }

        /* FONDO */
        .imagen-fondo {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -2;
        }

        .fondo-oscuro {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: -1;
        }

        /* CONTENEDOR PRINCIPAL */
        #pantalla-admin {
            position: relative;
            min-height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            text-align: center;
            box-sizing: border-box;
        }

        .contenedor-principal {
            position: relative;
            z-index: 3;
            padding: 20px;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 1200px;
        }

        /* TIPOGRAFÍA */
        h1 {
            font-size: 3rem;
            margin-top: 60px; /* Margen superior para no chocar con los botones */
            margin-bottom: 10px;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            font-weight: 700;
        }

        .subtitulo {
            font-size: 1.2rem;
            color: #ecf0f1;
            margin-bottom: 40px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
            font-weight: 400;
        }

        /* ICONO GRANDE */
        .icono-grande svg {
            width: 70px;
            height: 70px;
            fill: #f1c40f;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
            margin-bottom: 20px;
        }

        /* --- BARRA DE ACCIONES (CONTENEDOR DE TODOS LOS BOTONES) --- */
        .acciones-admin {
            position: fixed;   /* Se queda fijo en la pantalla */
            top: 30px;         /* Distancia desde arriba */
            right: 40px;       /* Distancia desde la derecha */
            display: flex;     /* Alinea los botones en fila */
            align-items: center;
            gap: 15px;         /* ESPACIO AUTOMÁTICO ENTRE BOTONES */
            z-index: 9999;     /* Siempre encima de todo */
        }

        /* ESTILOS DE LOS BOTONES */
        .boton-cerrar-sesion,
        .boton-agregar-libro, 
        .boton-agregar-genero,
        .boton-ver-generos { /* AÑADIDO AQUI */
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            border: none;
            cursor: pointer;
            white-space: nowrap;
            transition: transform 0.2s ease, background-color 0.2s;
            font-family: inherit;
        }

        /* Estilo Específico: Cerrar Sesión */
        .boton-cerrar-sesion {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            background: rgba(0, 0, 0, 0.5);
            padding: 10px 18px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-right: 10px; /* Separación extra visual del grupo de colores */
            font-weight: 600;
        }

        .boton-cerrar-sesion:hover {
            background: rgba(231, 76, 60, 0.9);
            border-color: #c0392b;
        }

        .boton-cerrar-sesion svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
            margin-right: 6px;
        }

        /* Estilo Específico: Botones de Acción (Colores) */
        .boton-agregar-libro, 
        .boton-agregar-genero,
        .boton-ver-generos { /* AÑADIDO AQUI */
            color: white;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 50px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }

        .boton-agregar-libro { background-color: #e67e22; }
        .boton-agregar-genero { background-color: #2980b9; }
        .boton-ver-generos { background-color: #8e44ad; } /* Color Morado para Ver Géneros */

        .boton-agregar-libro:hover,
        .boton-agregar-genero:hover,
        .boton-ver-generos:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        .boton-agregar-libro svg, 
        .boton-agregar-genero svg,
        .boton-ver-generos svg {
            width: 20px;
            height: 20px;
            fill: white;
            margin-right: 8px;
        }

        /* CUADRICULA */
        .cuadricula {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 30px;
            padding: 0 20px 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* TARJETA */
        .tarjeta {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 15px;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
            box-sizing: border-box;
        }

        .icono-tarjeta {
            width: 100%;
            aspect-ratio: 2/3;
            overflow: hidden;
            border-radius: 10px;
            margin-bottom: 15px;
            cursor: pointer;
        }

        .icono-tarjeta img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tarjeta h2 {
            font-size: 1.1rem;
            margin: 0;
            text-align: center;
            font-weight: 600;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            cursor: pointer;
        }

        /* BOTONES DE ACCIÓN */
        .botones-accion {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            width: 100%;
            justify-content: center;
        }

        .boton-editar, .boton-eliminar {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            text-align: center;
            flex: 1;
            max-width: 80px;
            color: white;
        }

        .boton-editar { background-color: #187224; }
        .boton-editar:hover { background-color: #187224; }

        .boton-eliminar { background-color: #f71e05; }
        .boton-eliminar:hover { background-color: #f71e05; }

        /* ESTILOS PARA LA MODAL */
        .fondo-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .contenido-modal {
            background: #2a2a2a;
            border-radius: 10px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            position: relative;
            border: 1px solid #444;
        }

        .boton-cerrar {
            position: absolute;
            top: 3px;
            right: 15px;
            color: #fff;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10;
            background: rgba(0, 0, 0, 0.5);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .boton-cerrar:hover { background: #e67e22; }

        .contenedor-modal { display: flex; height: 100%; }
        .seccion-imagen { width: 40%; background: #000; display: flex; align-items: center; justify-content: center; }
        .seccion-imagen img { width: 100%; height: 100%; object-fit: cover; }
        .seccion-info { width: 60%; padding: 20px; color: white; overflow-y: auto; }
        .etiqueta-genero { color: #e67e22; font-weight: bold; font-size: 14px; display: block; margin-bottom: 10px; }
        .seccion-info h2 { font-size: 24px; margin: 0 0 10px 0; line-height: 1.2; }
        .autor-modal { color: #ccc; font-style: italic; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #444; }
        .descripcion-modal { line-height: 1.5; color: #e0e0e0; }
        
        /* Estilos adicionales que tenías en el head */
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
            color: #333;
        }
        
        /* RESPONSIVE */
        @media (max-width: 950px) {
            .acciones-admin {
                position: relative; /* En móviles deja de ser fijo para no tapar contenido */
                top: auto;
                right: auto;
                justify-content: center;
                flex-wrap: wrap;
                margin-bottom: 20px;
                background: rgba(0,0,0,0.5); /* Fondo oscuro para que se distinga */
                padding: 15px;
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>

    <div id="pantalla-admin">
        
        <img src="images/registro.jpg" alt="Fondo Administración" class="imagen-fondo">
        <div class="fondo-oscuro"></div>

        <div class="contenedor-principal">
            
            <div class="acciones-admin">
                <a href="../vista/cerrar.php" class="boton-cerrar-sesion">
                    <svg viewBox="0 0 24 24"><path d="M10.09 15.59L11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59zM19 3H5c-1.11 0-2 .9-2 2v4h2V5h14v14H5v-4H3v4c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                    Cerrar Sesión
                </a>

                <a href="admin_generos.php" class="boton-ver-generos">
                    <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                    Ver Géneros
                </a>

                <a href="agregar_genero.php" class="boton-agregar-genero">
                    <svg viewBox="0 0 24 24"><path d="M17.63 5.84C17.27 5.33 16.67 5 16 5L5 5.01C3.9 5.01 3 5.9 3 7v10c0 1.1.9 1.99 2 1.99L16 19c.67 0 1.27-.33 1.63-.84L22 12l-4.37-6.16zM16 17H5V7h11l3.55 5L16 17z"/></svg>
                    Agregar Género
                </a>

                <a href="agregar.php" class="boton-agregar-libro">
                    <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                    Agregar Nuevo Libro
                </a>
            </div>
            
            
            <h1>Administración</h1>
            <p class="subtitulo">Gestiona el catálogo y los recursos de la biblioteca.</p>
            
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
                    tarjeta.style.display = 'flex'; 
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