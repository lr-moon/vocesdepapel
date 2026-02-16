<?php
session_start();
if(!isset($_SESSION['id']) || !isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}
?>
<?php
// Conexión a la base de datos
include('../controlador/conexion.php');

// Obtener el ID del libro desde la URL
$id_libro = isset($_GET['id_libro']) ? intval($_GET['id_libro']) : 0;

// Consulta para obtener los datos del libro
$sql_libro = "SELECT * FROM libro WHERE id_libro = ?";
$stmt_libro = $conn->prepare($sql_libro);
$stmt_libro->bind_param("i", $id_libro);
$stmt_libro->execute();
$result_libro = $stmt_libro->get_result();

if ($result_libro->num_rows === 0) {
    echo "<script>alert('Libro no encontrado'); window.location.href='admin_login.php';</script>";
    exit();
}

$libro = $result_libro->fetch_assoc();
$stmt_libro->close();

// Consulta para obtener los géneros actuales del libro
$sql_generos_libro = "SELECT lg.id_genero, lg.prioridad, g.nombre_genero 
                     FROM libro_genero lg 
                     JOIN genero g ON lg.id_genero = g.id_genero 
                     WHERE lg.id_libro = ? 
                     ORDER BY lg.prioridad";
$stmt_generos_libro = $conn->prepare($sql_generos_libro);
$stmt_generos_libro->bind_param("i", $id_libro);
$stmt_generos_libro->execute();
$result_generos_libro = $stmt_generos_libro->get_result();

// Almacenar los géneros actuales del libro
$generos_libro = [];
while($row = $result_generos_libro->fetch_assoc()) {
    $generos_libro[$row['prioridad']] = $row;
}
$stmt_generos_libro->close();

// Consulta para obtener todos los géneros disponibles
$sql_generos = "SELECT id_genero, nombre_genero FROM genero ORDER BY nombre_genero";
$result_generos = $conn->query($sql_generos);

if ($result_generos->num_rows > 0) {
    $generos = array();
    while($row = $result_generos->fetch_assoc()) {
        $generos[] = $row;
    }
} else {
    $generos = array();
}

// URL de imagen genérica de libro
define('IMAGEN_POR_DEFECTO', 'images/no_found.png');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Libro - Administración</title>
    <link rel="icon" type="image/png" href="images/favicon.ico"> 
    <link rel="stylesheet" href="style.css/agregar.css"> <!-- Usamos el mismo CSS que agregar -->
</head>
<body>

    <img src="images/admin.jpg" alt="Fondo Agregar" class="bg-image">
    <div class="bg-overlay"></div>

    <!-- HEADER -->
    <header>
        <div class="header-title">
            <!-- Icono Engranaje -->
            <svg style="width:28px; height:28px; fill:white;" viewBox="0 0 24 24">
                <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
            </svg>
            Panel Admin - Editar Libro
        </div>
        <a href="admin_login.php" class="btn-glass">
            <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
            Cancelar
        </a>
    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="main-container">
        <div class="form-container">
            <h2 class="form-title">Editar Libro</h2>
            
            <form action="../modelo/editar_libro.php" method="POST" id="form-libro" onsubmit="return validarGeneros()">
    
    <!-- ID Oculto -->
    <input type="hidden" name="id_libro" value="<?php echo htmlspecialchars($libro['id_libro']); ?>">

    <!-- Título -->
    <div class="form-group">
        <label class="form-label">Título del Libro</label>
        <input type="text" name="titulo" class="form-input" 
               value="<?php echo htmlspecialchars($libro['titulo']); ?>" required>
    </div>

    <!-- Autor -->
    <div class="form-group">
        <label class="form-label">Autor</label>
        <input type="text" name="autor" class="form-input" 
               value="<?php echo htmlspecialchars($libro['autor']); ?>" required>
    </div>

    <!-- Descripción -->
    <div class="form-group">
        <label class="form-label">Descripción / Sinopsis</label>
        <textarea name="descripcion" class="form-textarea" required><?php echo htmlspecialchars($libro['descripcion']); ?></textarea>
    </div>

    <!-- Ruta de Imagen -->
    <div class="form-group">
        <label class="form-label">Ruta de Imagen</label>
        <input type="text" name="ruta_imagen" class="form-input" 
               value="<?php echo htmlspecialchars($libro['ruta_imagen']); ?>"
               oninput="mostrarVistaPrevia(this.value)">
        <div id="vista-previa-container" style="margin-top: 10px; text-align: center;">
            <p style="font-size: 14px; margin-bottom: 5px;">Vista previa:</p>
            <img id="vista-previa" src="<?php echo htmlspecialchars($libro['ruta_imagen']); ?>" 
                 alt="Vista previa de la imagen" 
                 style="max-width: 200px; max-height: 300px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
    </div>

    <!-- Géneros (3 columnas) -->
    <div class="genres-row">
        <!-- Género Principal -->
        <div class="form-group">
            <label class="form-label">Género Principal</label>
            <select name="id_genero_1" id="id_genero_1" class="form-select" required>
                <option value="" disabled>Seleccionar...</option>
                <?php if (count($generos) > 0): ?>
                    <?php foreach ($generos as $genero): ?>
                        <option value="<?php echo $genero['id_genero']; ?>"
                            <?php echo (isset($generos_libro[1]) && $generos_libro[1]['id_genero'] == $genero['id_genero']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($genero['nombre_genero']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>No hay géneros disponibles</option>
                <?php endif; ?>
            </select>
            <input type="hidden" name="prioridad_1" value="1">
        </div>

        <!-- Género Secundario -->
        <div class="form-group">
            <label class="form-label">Género Secundario</label>
            <select name="id_genero_2" id="id_genero_2" class="form-select" required>
                <option value="" disabled>Seleccionar...</option>
                <?php if (count($generos) > 0): ?>
                    <?php foreach ($generos as $genero): ?>
                        <option value="<?php echo $genero['id_genero']; ?>"
                            <?php echo (isset($generos_libro[2]) && $generos_libro[2]['id_genero'] == $genero['id_genero']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($genero['nombre_genero']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>No hay géneros disponibles</option>
                <?php endif; ?>
            </select>
            <input type="hidden" name="prioridad_2" value="2">
        </div>

        <!-- Género Terciario -->
        <div class="form-group">
            <label class="form-label">Género Terciario</label>
            <select name="id_genero_3" id="id_genero_3" class="form-select" required>
                <option value="" disabled>Seleccionar...</option>
                <?php if (count($generos) > 0): ?>
                    <?php foreach ($generos as $genero): ?>
                        <option value="<?php echo $genero['id_genero']; ?>"
                            <?php echo (isset($generos_libro[3]) && $generos_libro[3]['id_genero'] == $genero['id_genero']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($genero['nombre_genero']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>No hay géneros disponibles</option>
                <?php endif; ?>
            </select>
            <input type="hidden" name="prioridad_3" value="3">
        </div>
    </div>
    
    <!-- Mensaje de error para géneros repetidos -->
    <div id="error-generos" style="color: #ff3860; background-color: #ffebee; padding: 10px; border-radius: 4px; margin-top: 10px; display: none;">
        <strong>Error:</strong> No se puede repetir el mismo género en diferentes campos.
    </div>

    <!-- Botón Enviar -->
    <button type="submit" class="btn-submit">
        Guardar Cambios <i class="fas fa-save" style="margin-left: 8px;"></i>
    </button>

</form>
        </div>
    </div>

<script>
// Definir la imagen por defecto (pasa el valor PHP a JavaScript)
const IMAGEN_POR_DEFECTO = '<?php echo IMAGEN_POR_DEFECTO; ?>';

// Función para mostrar vista previa de imagen
function mostrarVistaPrevia(ruta) {
    const vistaPrevia = document.getElementById('vista-previa');
    const container = document.getElementById('vista-previa-container');
    
    if (ruta.trim() !== '') {
        // Primero mostrar el contenedor
        container.style.display = 'block';
        
        // Intentar cargar la imagen especificada
        vistaPrevia.src = ruta;
        
        // Configurar eventos de carga
        vistaPrevia.onload = function() {
            console.log('Imagen cargada correctamente desde:', ruta);
        };
        
        vistaPrevia.onerror = function() {
            console.log('Error al cargar la imagen, mostrando imagen por defecto');
            // Mostrar imagen por defecto
            vistaPrevia.src = IMAGEN_POR_DEFECTO;
            vistaPrevia.alt = 'Imagen por defecto - Libro genérico';
        };
    } else {
        container.style.display = 'none';
    }
}

// Función para validar que no haya géneros repetidos
function validarGeneros() {
    const genero1 = document.getElementById('id_genero_1').value;
    const genero2 = document.getElementById('id_genero_2').value;
    const genero3 = document.getElementById('id_genero_3').value;
    const errorDiv = document.getElementById('error-generos');
    
    // Verificar si hay valores repetidos
    if ((genero1 === genero2 && genero1 !== '' && genero2 !== '') ||
        (genero1 === genero3 && genero1 !== '' && genero3 !== '') ||
        (genero2 === genero3 && genero2 !== '' && genero3 !== '')) {
        
        // Mostrar mensaje de error
        errorDiv.style.display = 'block';
        
        // Hacer scroll al mensaje de error
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Prevenir el envío del formulario
        return false;
    }
    
    // Ocultar mensaje de error si todo está bien
    errorDiv.style.display = 'none';
    return true;
}

// Agregar event listeners para validación en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const select1 = document.getElementById('id_genero_1');
    const select2 = document.getElementById('id_genero_2');
    const select3 = document.getElementById('id_genero_3');
    
    // Validar cuando cambie cualquier selector
    [select1, select2, select3].forEach(select => {
        select.addEventListener('change', function() {
            validarGeneros();
        });
    });
    
    // Prevenir selecciones duplicadas en tiempo real
    [select1, select2, select3].forEach(select => {
        select.addEventListener('change', function() {
            const selects = [select1, select2, select3];
            const currentSelect = this;
            const currentValue = this.value;
            
            // Si se selecciona un valor vacío, no hacer nada
            if (currentValue === '') return;
            
            // Deshabilitar esta opción en los otros selects
            selects.forEach(otherSelect => {
                if (otherSelect !== currentSelect) {
                    // Habilitar todas las opciones primero
                    Array.from(otherSelect.options).forEach(option => {
                        option.disabled = false;
                    });
                    
                    // Deshabilitar la opción seleccionada en otros selects
                    Array.from(otherSelect.options).forEach(option => {
                        if (option.value === currentValue) {
                            option.disabled = true;
                        }
                    });
                }
            });
        });
    });
    
    // Ejecutar validación inicial
    validarGeneros();
});
</script>
</body>
</html>