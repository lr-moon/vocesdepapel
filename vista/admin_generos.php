<?php
// Conexión a la base de datos
include ('../controlador/conexion.php');

// Procesar eliminación de género
if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);
    
    // Verificar si el género existe
    $sql_check = "SELECT * FROM genero WHERE id_genero = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id_eliminar);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        // Verificar si hay libros asociados a este género
        $sql_check_libros = "SELECT COUNT(*) as total FROM libro_genero WHERE id_genero = ?";
        $stmt_check_libros = $conn->prepare($sql_check_libros);
        $stmt_check_libros->bind_param("i", $id_eliminar);
        $stmt_check_libros->execute();
        $result_check_libros = $stmt_check_libros->get_result();
        $row_libros = $result_check_libros->fetch_assoc();
        
        if ($row_libros['total'] > 0) {
            $mensaje_error = "No se puede eliminar este género porque tiene libros asociados. Primero elimine o modifique los libros relacionados.";
        } else {
            // Eliminar el género
            $sql_delete = "DELETE FROM genero WHERE id_genero = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("i", $id_eliminar);
            
            if ($stmt_delete->execute()) {
                $mensaje_exito = "Género eliminado exitosamente.";
            } else {
                $mensaje_error = "Error al eliminar el género: " . $stmt_delete->error;
            }
            $stmt_delete->close();
        }
        $stmt_check_libros->close();
    } else {
        $mensaje_error = "El género no existe.";
    }
    $stmt_check->close();
}

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
    <title>Administrar Géneros - Biblioteca</title>
    <link rel="icon" type="image/png" href="images/favicon.ico"> 
    <link rel="stylesheet" href="style.css/generos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos para los mensajes de confirmación */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-confirmacion {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        
        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        
        .btn-confirmar {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-cancelar {
            background: #95a5a6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-confirmar:hover {
            background: #c0392b;
        }
        
        .btn-cancelar:hover {
            background: #7f8c8d;
        }
        
        /* Estilos para mensajes de éxito/error */
        .mensaje-flotante {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 5px;
            color: white;
            z-index: 1001;
            animation: slideIn 0.3s ease-out;
        }
        
        .mensaje-exito {
            background: #27ae60;
            border-left: 5px solid #2ecc71;
        }
        
        .mensaje-error {
            background: #c0392b;
            border-left: 5px solid #e74c3c;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>

    <img src="images/hojas.jpg" alt="Fondo Hojas" class="bg-image">
    <div class="bg-overlay"></div>

    <!-- Mensajes flotantes -->
    <?php if (isset($mensaje_exito)): ?>
        <div class="mensaje-flotante mensaje-exito" id="mensaje-exito">
            <?php echo htmlspecialchars($mensaje_exito); ?>
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('mensaje-exito').style.display = 'none';
            }, 5000);
        </script>
    <?php endif; ?>
    
    <?php if (isset($mensaje_error)): ?>
        <div class="mensaje-flotante mensaje-error" id="mensaje-error">
            <?php echo htmlspecialchars($mensaje_error); ?>
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('mensaje-error').style.display = 'none';
            }, 5000);
        </script>
    <?php endif; ?>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal-overlay" id="modal-confirmacion">
        <div class="modal-confirmacion">
            <h3 style="margin-bottom: 15px; color: #333;">Confirmar Eliminación</h3>
            <p id="mensaje-confirmacion" style="color: #555; margin-bottom: 20px;"></p>
            <div class="modal-buttons">
                <button class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                <button class="btn-confirmar" onclick="confirmarEliminacion()">Sí, Eliminar</button>
            </div>
        </div>
    </div>

    <header>
        <div class="header-title">
            <svg style="width:28px; height:28px; fill:white; margin-right:10px;" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            Admin Géneros
        </div>

        <div class="header-buttons">
            <a href="admin_login.php" class="btn-glass">
                <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                Volver al Panel
            </a>
            
            <a href="agregar_genero.php" class="btn-glass btn-add">
                <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                Nuevo Género
            </a>
        </div>
    </header>

    <div class="container">
        
        <div class="page-intro">
            <h2>Gestión de Categorías</h2>
            <p>Edita o elimina los géneros literarios del sistema.</p>
        </div>

        <div class="grid">
            <?php if (count($generos) > 0): ?>
                <?php foreach ($generos as $genero): ?>
                    <div class="card">
                        <div class="card-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 2l-5.5 9h11L12 2zm0 3.84L13.93 9h-3.87L12 5.84zM17.5 13c-2.48 0-4.5 2.02-4.5 4.5s2.02 4.5 4.5 4.5 4.5-2.02 4.5-4.5-2.02-4.5-4.5-4.5zm0 7c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5zM3 13c-2.48 0-4.5 2.02-4.5 4.5S.52 22 3 22s4.5-2.02 4.5-4.5S5.48 13 3 13zm0 7c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                        </div>
                        <h3><?php echo htmlspecialchars($genero['nombre_genero']); ?></h3>
                        <p><?php echo !empty($genero['descripcion']) ? htmlspecialchars($genero['descripcion']) : 'Sin descripción'; ?></p>
                        
                        <div class="card-actions">
                            <a href="edit_genero.php?id_genero=<?php echo $genero['id_genero']; ?>" class="btn-action btn-edit">
                                Editar
                            </a>
                            <button class="btn-action btn-delete" 
                                    onclick="mostrarConfirmacion(<?php echo $genero['id_genero']; ?>, '<?php echo addslashes($genero['nombre_genero']); ?>')">
                                Eliminar
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-generos">
                    <p>No hay géneros registrados.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<script>
// Variables globales para almacenar el ID del género a eliminar
let generoIdAEliminar = null;
let generoNombreAEliminar = '';

// Función para mostrar el modal de confirmación
function mostrarConfirmacion(id, nombre) {
    generoIdAEliminar = id;
    generoNombreAEliminar = nombre;
    
    const modal = document.getElementById('modal-confirmacion');
    const mensaje = document.getElementById('mensaje-confirmacion');
    
    mensaje.textContent = `¿Está seguro de que desea eliminar el género "${nombre}"? Esta acción no se puede deshacer.`;
    modal.style.display = 'flex';
}

// Función para cerrar el modal
function cerrarModal() {
    const modal = document.getElementById('modal-confirmacion');
    modal.style.display = 'none';
    generoIdAEliminar = null;
    generoNombreAEliminar = '';
}

// Función para confirmar la eliminación
function confirmarEliminacion() {
    if (generoIdAEliminar) {
        // Redirigir para eliminar el género
        window.location.href = `?eliminar=${generoIdAEliminar}`;
    }
}

// Cerrar modal al hacer clic fuera del contenido
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-confirmacion');
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            cerrarModal();
        }
    });
    
    // Cerrar con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal();
        }
    });
});

// Auto-cerrar mensajes después de 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    const mensajes = document.querySelectorAll('.mensaje-flotante');
    
    mensajes.forEach(function(mensaje) {
        setTimeout(function() {
            mensaje.style.opacity = '0';
            setTimeout(function() {
                mensaje.style.display = 'none';
            }, 300);
        }, 5000);
    });
});
</script>

</body>
</html>