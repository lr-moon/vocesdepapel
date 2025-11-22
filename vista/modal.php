<!-- ESTILOS DE LA VENTANA MODAL -->
<style>
    /* Fondo oscuro que cubre la pantalla (Overlay) */
    .modal-overlay {
        display: none; /* Oculto por defecto */
        position: fixed;
        z-index: 2000; /* Por encima de todo */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.85); /* Fondo negro semitransparente */
        backdrop-filter: blur(5px); /* Efecto borroso en el fondo */
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    /* Clase activa para mostrar la modal */
    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    /* Tarjeta de contenido */
    .modal-content {
        background: rgba(30, 30, 30, 0.95); /* Gris oscuro casi sólido */
        border: 1px solid rgba(255, 255, 255, 0.1);
        width: 90%;
        max-width: 900px; /* Ancho máximo */
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        position: relative;
        display: flex;
        overflow: hidden;
        max-height: 90vh; /* Altura máxima */
        transform: scale(0.8);
        transition: transform 0.3s ease;
    }

    /* Animación de entrada */
    .modal-overlay.active .modal-content {
        transform: scale(1);
    }

    /* Botón de Cerrar (X) */
    .close-btn {
        position: absolute;
        top: 15px;
        right: 20px;
        color: #aaa;
        font-size: 30px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10;
        line-height: 1;
        transition: color 0.3s;
        background: rgba(0,0,0,0.5);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-btn:hover {
        color: #e67e22;
        background: rgba(255,255,255,0.1);
    }

    /* Estructura Interna: Imagen + Info */
    .modal-body {
        display: flex;
        width: 100%;
    }

    /* Columna Izquierda: Imagen */
    .modal-image-container {
        width: 40%;
        background-color: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .modal-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.95;
    }

    /* Columna Derecha: Información */
    .modal-info-container {
        width: 60%;
        padding: 40px;
        overflow-y: auto; /* Scroll si el texto es largo */
        text-align: left;
        color: white;
    }

    /* Tipografía */
    .modal-genre {
        color: #e67e22; /* Naranja */
        font-weight: bold;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 2px;
        display: block;
        margin-bottom: 10px;
    }

    .modal-title {
        font-size: 2.2rem;
        margin: 0 0 10px 0;
        font-weight: 700;
        line-height: 1.1;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }

    .modal-author {
        font-size: 1.1rem;
        color: #ccc;
        font-style: italic;
        display: block;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .modal-description {
        font-size: 1rem;
        line-height: 1.6;
        color: #e0e0e0;
    }

    /* Diseño Responsivo (Móviles) */
    @media (max-width: 768px) {
        .modal-content {
            flex-direction: column;
            max-height: 95vh;
            overflow-y: auto;
        }
        .modal-image-container {
            width: 100%;
            height: 300px;
            min-height: auto;
        }
        .modal-info-container {
            width: 100%;
            padding: 25px;
        }
        .modal-title {
            font-size: 1.8rem;
        }
    }
</style>

<!-- ESTRUCTURA HTML (Invisible por defecto) -->
<!-- Al hacer clic fuera de la tarjeta (en el fondo), se cierra la modal -->
<div id="libroModal" class="modal-overlay" onclick="cerrarModalClick(event)">
    <div class="modal-content">
        <!-- Botón de cerrar -->
        <span class="close-btn" onclick="cerrarModal()">&times;</span>
        
        <div class="modal-body">
            <!-- Imagen del Libro -->
            <div class="modal-image-container">
                <img id="modalImg" src="" alt="Portada del Libro">
            </div>
            
            <!-- Información del Libro -->
            <div class="modal-info-container">
                <span id="modalGenero" class="modal-genre">Género</span>
                <h2 id="modalTitulo" class="modal-title">Título del Libro</h2>
                <span id="modalAutor" class="modal-author">Autor</span>
                
                <div class="modal-description">
                    <p id="modalDescripcion">Descripción del libro...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT DE FUNCIONALIDAD -->
<script>
    /**
     * Abre la modal y rellena los datos dinámicamente.
     * Esta función es llamada desde select_genero.php
     */
    function abrirModal(titulo, autor, genero, descripcion, imagen) {
        // 1. Rellenar los campos con la información recibida
        document.getElementById('modalTitulo').innerText = titulo;
        document.getElementById('modalAutor').innerText = autor;
        document.getElementById('modalGenero').innerText = genero;
        document.getElementById('modalDescripcion').innerText = descripcion;
        document.getElementById('modalImg').src = imagen;

        // 2. Mostrar la modal
        document.getElementById('libroModal').classList.add('active');
        
        // 3. Bloquear el scroll de la página de fondo
        document.body.style.overflow = 'hidden';
    }

    /**
     * Cierra la modal y limpia el estado.
     */
    function cerrarModal() {
        // 1. Ocultar la modal
        document.getElementById('libroModal').classList.remove('active');
        
        // 2. Restaurar el scroll
        document.body.style.overflow = 'auto';
    }

    /**
     * Detecta clic fuera de la tarjeta para cerrar.
     */
    function cerrarModalClick(event) {
        if (event.target.id === 'libroModal') {
            cerrarModal();
        }
    }
    
    // Cerrar con la tecla ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            cerrarModal();
        }
    });
</script>