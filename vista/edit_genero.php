<?php
// Conexión a la base de datos
include('../controlador/conexion.php');

// Obtener el ID del género desde la URL
$id_genero = isset($_GET['id_genero']) ? intval($_GET['id_genero']) : 0;

// Variables para mensajes y datos
$mensaje = '';
$error = '';
$nombre_genero = '';
$descripcion_genero = '';

// Consultar el género actual
if ($id_genero > 0) {
    $sql_genero = "SELECT * FROM genero WHERE id_genero = ?";
    $stmt_genero = $conn->prepare($sql_genero);
    $stmt_genero->bind_param("i", $id_genero);
    $stmt_genero->execute();
    $result_genero = $stmt_genero->get_result();
    
    if ($result_genero->num_rows > 0) {
        $genero = $result_genero->fetch_assoc();
        $nombre_genero = $genero['nombre_genero'];
        $descripcion_genero = $genero['descripcion'];
    } else {
        $error = 'Género no encontrado';
    }
    $stmt_genero->close();
} else {
    $error = 'ID de género no válido';
}

// Procesar el formulario cuando se envía por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y limpiar datos
    $id_genero = intval($_POST['id_genero']);
    $nombre_genero = trim($_POST['nombre_genero']);
    $descripcion_genero = trim($_POST['descripcion_genero']);
    
    // Validar campos vacíos
    if (empty($nombre_genero) || empty($descripcion_genero)) {
        $error = 'Todos los campos son obligatorios';
    }
    // Validar longitud del nombre
    elseif (strlen($nombre_genero) > 100) {
        $error = 'El nombre del género no puede tener más de 100 caracteres';
    }
    // Validar número de palabras en la descripción
    elseif (str_word_count($descripcion_genero, 0, 'áéíóúÁÉÍÓÚñÑ') > 50) {
        $error = 'La descripción no puede tener más de 50 palabras';
    }
    // Validar longitud de la descripción
    elseif (strlen($descripcion_genero) > 1000) {
        $error = 'La descripción es demasiado larga (máximo 1000 caracteres)';
    }
    // Validar formato del nombre
    elseif (!preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s\-]+$/', $nombre_genero)) {
        $error = 'El nombre solo puede contener letras, números, espacios y guiones';
    }
    // Si no hay errores, proceder con la actualización
    else {
        // Verificar si el nuevo nombre ya existe (excluyendo el actual)
        $sql_check_nombre = "SELECT id_genero FROM genero WHERE LOWER(nombre_genero) = LOWER(?) AND id_genero != ?";
        $stmt_check_nombre = $conn->prepare($sql_check_nombre);
        $stmt_check_nombre->bind_param("si", $nombre_genero, $id_genero);
        $stmt_check_nombre->execute();
        $result_check_nombre = $stmt_check_nombre->get_result();
        
        if ($result_check_nombre->num_rows > 0) {
            $error = 'Este nombre de género ya existe en la base de datos';
            $stmt_check_nombre->close();
        } else {
            $stmt_check_nombre->close();
            
            // Actualizar el género
            $sql_update = "UPDATE genero SET nombre_genero = ?, descripcion = ? WHERE id_genero = ?";
            $stmt_update = $conn->prepare($sql_update);
            
            if ($stmt_update) {
                $stmt_update->bind_param("ssi", $nombre_genero, $descripcion_genero, $id_genero);
                
                if ($stmt_update->execute()) {
                    $mensaje = 'Género actualizado exitosamente';
                    
                    // Redirigir después de 2 segundos
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'admin_generos.php';
                            }, 2000);
                          </script>";
                } else {
                    $error = 'Error al actualizar el género: ' . $stmt_update->error;
                }
                
                $stmt_update->close();
            } else {
                $error = 'Error al preparar la consulta de actualización';
            }
        }
    }
}

// Consulta para obtener géneros existentes para validación en JavaScript
$sql_generos = "SELECT id_genero, nombre_genero FROM genero";
$result_generos = $conn->query($sql_generos);

$generos_existentes = array();
$descripciones_existentes = array();

if ($result_generos->num_rows > 0) {
    while($row = $result_generos->fetch_assoc()) {
        $generos_existentes[$row['id_genero']] = strtolower(trim($row['nombre_genero']));
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Género - Administración</title>
    <link rel="stylesheet" href="style.css/agregar_genero.css">
</head>
<body>

    <img src="images/admin.jpg" alt="Fondo Agregar" class="bg-image">
    <div class="bg-overlay"></div>

    <header>
        <div class="header-title">
            <svg style="width:28px; height:28px; fill:white;" viewBox="0 0 24 24">
                <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
            </svg>
            Panel Admin - Editar Género
        </div>
        <a href="admin_generos.php" class="btn-glass">
            <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
            Cancelar
        </a>
    </header>

    <div class="main-container">
        <div class="form-container">
            <h2 class="form-title">Editar Género</h2>
            
            <?php if ($error && $id_genero == 0): ?>
                <div class="mensaje-error" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
                    <?php echo htmlspecialchars($error); ?>
                    <br><br>
                    <a href="admin_generos.php" style="color: #721c24; text-decoration: underline;">Volver a la lista de géneros</a>
                </div>
            <?php else: ?>
            
                <!-- Mostrar mensajes de éxito o error -->
                <?php if ($mensaje): ?>
                    <div class="mensaje-exito" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c3e6cb;">
                        <?php echo htmlspecialchars($mensaje); ?>
                        <br><small>Redirigiendo en 2 segundos...</small>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="mensaje-error" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" id="form-genero" onsubmit="return validarFormulario()">
                    <input type="hidden" name="id_genero" value="<?php echo $id_genero; ?>">
                    
                    <div class="form-group">
                        <label class="form-label">Nombre del Género</label>
                        <input type="text" name="nombre_genero" id="nombre_genero" class="form-input" 
                               placeholder="Ej: Ciencia Ficción" 
                               value="<?php echo htmlspecialchars($nombre_genero); ?>"
                               oninput="validarNombre()"
                               required>
                        <div id="error-nombre" class="mensaje-error" style="display: none;">
                            <span id="texto-error-nombre"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion_genero" id="descripcion_genero" class="form-textarea" 
                                  placeholder="Describe brevemente de qué trata este género (máximo 50 palabras)..." 
                                  oninput="contarPalabras()"
                                  required><?php echo htmlspecialchars($descripcion_genero); ?></textarea>
                        <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                            <div id="contador-palabras" style="font-size: 12px; color: #666;">
                                Palabras: 0/50
                            </div>
                            <div id="error-descripcion" class="mensaje-error" style="display: none;">
                                <span id="texto-error-descripcion"></span>
                            </div>
                        </div>
                    </div>

                    <div id="error-general" class="mensaje-error" style="display: none; margin-top: 15px;">
                        <span id="texto-error-general"></span>
                    </div>

                    <button type="submit" class="btn-submit">
                        Actualizar Género
                    </button>

                </form>
            <?php endif; ?>
        </div>
    </div>

<script>
// Pasar los datos de PHP a JavaScript
const generosExistentes = <?php echo json_encode($generos_existentes); ?>;
const idGeneroActual = <?php echo $id_genero; ?>;

// Función para contar palabras en JavaScript
function contarPalabras() {
    const textarea = document.getElementById('descripcion_genero');
    const contador = document.getElementById('contador-palabras');
    const texto = textarea.value.trim();
    
    // Contar palabras de forma más precisa
    const palabras = texto === '' ? 0 : texto.split(/\s+/).filter(word => word.length > 0).length;
    contador.textContent = `Palabras: ${palabras}/50`;
    
    // Cambiar color si se excede el límite
    if (palabras > 50) {
        contador.style.color = '#e74c3c';
        contador.style.fontWeight = 'bold';
    } else {
        contador.style.color = '#666';
        contador.style.fontWeight = 'normal';
    }
}

// Función para validar el nombre del género
function validarNombre() {
    const nombreInput = document.getElementById('nombre_genero');
    const nombre = nombreInput.value.trim();
    const errorDiv = document.getElementById('error-nombre');
    const errorText = document.getElementById('texto-error-nombre');
    
    // Limpiar error
    errorDiv.style.display = 'none';
    errorText.textContent = '';
    nombreInput.classList.remove('error');
    
    if (nombre === '') {
        return false;
    }
    
    // Validar longitud (100 caracteres según schema)
    if (nombre.length > 100) {
        errorText.textContent = 'El nombre no puede tener más de 100 caracteres';
        errorDiv.style.display = 'block';
        nombreInput.classList.add('error');
        return false;
    }
    
    // Validar si ya existe (case-insensitive, excluyendo el actual)
    const nombreLower = nombre.toLowerCase();
    for (const [id, nombreExistente] of Object.entries(generosExistentes)) {
        if (id != idGeneroActual && nombreExistente === nombreLower) {
            errorText.textContent = 'Este nombre de género ya existe en la base de datos';
            errorDiv.style.display = 'block';
            nombreInput.classList.add('error');
            return false;
        }
    }
    
    // Validar formato básico
    const regex = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s\-]+$/;
    if (!regex.test(nombre)) {
        errorText.textContent = 'El nombre solo puede contener letras, números, espacios y guiones';
        errorDiv.style.display = 'block';
        nombreInput.classList.add('error');
        return false;
    }
    
    return true;
}

// Función para validar la descripción
function validarDescripcion() {
    const descripcionInput = document.getElementById('descripcion_genero');
    const descripcion = descripcionInput.value.trim();
    const errorDiv = document.getElementById('error-descripcion');
    const errorText = document.getElementById('texto-error-descripcion');
    
    // Limpiar error
    errorDiv.style.display = 'none';
    errorText.textContent = '';
    descripcionInput.classList.remove('error');
    
    if (descripcion === '') {
        return false;
    }
    
    // Contar palabras
    const palabras = descripcion === '' ? 0 : descripcion.split(/\s+/).filter(word => word.length > 0).length;
    
    // Validar número de palabras
    if (palabras > 50) {
        errorText.textContent = 'La descripción no puede tener más de 50 palabras';
        errorDiv.style.display = 'block';
        descripcionInput.classList.add('error');
        return false;
    }
    
    // Validar longitud máxima
    if (descripcion.length > 1000) {
        errorText.textContent = 'La descripción no puede tener más de 1000 caracteres';
        errorDiv.style.display = 'block';
        descripcionInput.classList.add('error');
        return false;
    }
    
    return true;
}

// Función principal de validación del formulario
function validarFormulario() {
    const nombreInput = document.getElementById('nombre_genero');
    const descripcionInput = document.getElementById('descripcion_genero');
    const errorGeneralDiv = document.getElementById('error-general');
    const errorGeneralText = document.getElementById('texto-error-general');
    
    // Limpiar mensajes de error general
    errorGeneralDiv.style.display = 'none';
    errorGeneralText.textContent = '';
    
    // Validar que no estén vacíos
    if (nombreInput.value.trim() === '' || descripcionInput.value.trim() === '') {
        errorGeneralText.textContent = 'Por favor complete todos los campos';
        errorGeneralDiv.style.display = 'block';
        return false;
    }
    
    // Validar nombre
    if (!validarNombre()) {
        errorGeneralText.textContent = 'Por favor corrija los errores en el nombre del género';
        errorGeneralDiv.style.display = 'block';
        return false;
    }
    
    // Validar descripción
    if (!validarDescripcion()) {
        errorGeneralText.textContent = 'Por favor corrija los errores en la descripción';
        errorGeneralDiv.style.display = 'block';
        return false;
    }
    
    // Validar número final de palabras
    const palabras = descripcionInput.value.trim().split(/\s+/).filter(word => word.length > 0).length;
    if (palabras > 50) {
        errorGeneralText.textContent = 'La descripción no puede tener más de 50 palabras';
        errorGeneralDiv.style.display = 'block';
        return false;
    }
    
    return confirm('¿Está seguro de que desea actualizar este género?');
}

// Event listeners para validación en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const nombreInput = document.getElementById('nombre_genero');
    const descripcionInput = document.getElementById('descripcion_genero');
    
    // Validar nombre en tiempo real (con retardo para mejor rendimiento)
    let nombreTimeout;
    nombreInput.addEventListener('input', function() {
        clearTimeout(nombreTimeout);
        nombreTimeout = setTimeout(validarNombre, 500);
    });
    
    // Validar nombre al perder foco
    nombreInput.addEventListener('blur', validarNombre);
    
    // Contar palabras en tiempo real
    descripcionInput.addEventListener('input', contarPalabras);
    
    // Validar descripción en tiempo real (con retardo)
    let descripcionTimeout;
    descripcionInput.addEventListener('input', function() {
        clearTimeout(descripcionTimeout);
        descripcionTimeout = setTimeout(function() {
            contarPalabras();
            validarDescripcion();
        }, 500);
    });
    
    // Validar descripción al perder foco
    descripcionInput.addEventListener('blur', function() {
        contarPalabras();
        validarDescripcion();
    });
    
    // Inicializar contador con el valor actual
    contarPalabras();
});
</script>

</body>
</html>