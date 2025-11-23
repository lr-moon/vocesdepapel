<?php
// Recibimos los datos del libro desde la URL (enviados desde admin_panel.php)
// Si no hay datos, ponemos cadenas vacías para evitar errores
$id_libro = isset($_GET['id_libro']) ? $_GET['id_libro'] : '';
$titulo = isset($_GET['titulo']) ? $_GET['titulo'] : '';
$autor = isset($_GET['autor']) ? $_GET['autor'] : '';
$genero = isset($_GET['genero']) ? $_GET['genero'] : '';
$descripcion = isset($_GET['descripcion']) ? $_GET['descripcion'] : '';
$ruta_imagen = isset($_GET['ruta_imagen']) ? $_GET['ruta_imagen'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Libro - Administración</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<link rel="stylesheet" href="style.css/editar_libro.css">
<body>

    <!-- IMAGEN DE FONDO -->
    <img src="images/editar.jpg" alt="Fondo Administración" class="bg-image">
    <div class="bg-overlay"></div>

    <!-- HEADER -->
    <header>
        <div class="header-title">
            <i class="fas fa-edit" style="margin-right: 10px;"></i> Editor de Libro
        </div>
        <a href="admin_login.php" class="btn-glass">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Cancelar
        </a>
    </header>

    <!-- FORMULARIO CENTRAL -->
    <div class="main-container">
        <div class="form-container">
            <h2 class="form-title">Editar Información</h2>
            
            <!-- El formulario envía los datos a un archivo PHP que procese la actualización -->
            <form action="../modelo/editar_libro.php" method="POST">
                
                <!-- ID Oculto (Necesario para saber qué libro actualizar) -->
                <input type="hidden" name="id_libro" value="<?php echo htmlspecialchars($id_libro); ?>">

                <!-- Título (SOLO LECTURA) -->
                <div class="form-group">
                    <label class="form-label">Título del Libro (No editable)</label>
                    <input type="text" class="form-input" name="titulo" 
                           value="<?php echo htmlspecialchars($titulo); ?>" readonly>
                </div>

                <!-- Autor (SOLO LECTURA) -->
                <div class="form-group">
                    <label class="form-label">Autor (No editable)</label>
                    <input type="text" class="form-input" name="autor" 
                           value="<?php echo htmlspecialchars($autor); ?>" readonly>
                </div>

                <!-- Género (SOLO LECTURA) -->
                <div class="form-group">
                    <label class="form-label">Género Principal (No editable)</label>
                    <!-- Usamos input text en lugar de select para mostrarlo fijo -->
                    <input type="text" class="form-input" name="genero" 
                           value="<?php echo htmlspecialchars($genero); ?>" readonly>
                </div>

                <!-- Descripción (EDITABLE) -->
                <div class="form-group">
                    <label class="form-label" style="color: #e67e22;">Descripción / Sinopsis (Editable)</label>
                    <textarea class="form-textarea" name="descripcion" required><?php echo htmlspecialchars($descripcion); ?></textarea>
                </div>

                <!-- Ruta de Imagen (EDITABLE) -->
                <div class="form-group">
                    <label class="form-label" style="color: #e67e22;">Ruta de Imagen (Editable)</label>
                    <input type="text" class="form-input" name="ruta_imagen" 
                           value="<?php echo htmlspecialchars($ruta_imagen); ?>" oninput="mostrarVistaPrevia(this.value)" required>
                    <div id="vista-previa-container" style="margin-top: 10px; display: none; text-align: center;">
                        <p style="font-size: 14px; margin-bottom: 5px;">Vista previa:</p>
                        <img id="vista-previa" src="" alt="Vista previa de la imagen" style="max-width: 200px; max-height: 300px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-save" style="margin-right: 8px;"></i> Guardar Cambios
                </button>

            </form>
        </div>
    </div>
<script>
function mostrarVistaPrevia(ruta) {
    const vistaPrevia = document.getElementById('vista-previa');
    const container = document.getElementById('vista-previa-container');
    
    if (ruta.trim() !== '') {
        vistaPrevia.src = ruta;
        container.style.display = 'block';
        
        // Verificar si la imagen carga correctamente
        vistaPrevia.onload = function() {
            console.log('Imagen cargada correctamente');
        };
        
        vistaPrevia.onerror = function() {
            vistaPrevia.alt = 'Imagen no encontrada';
            console.log('Error al cargar la imagen');
        };
    } else {
        container.style.display = 'none';
    }
}
</script>
</body>
</html>