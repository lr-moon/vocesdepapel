<div id="modal-libro" class="fondo-modal">
    <div class="contenido-modal">
        <span class="boton-cerrar" onclick="cerrarModal()">&times;</span>
        
        <div class="contenedor-modal">
            <div class="seccion-imagen">
                <img id="modal-imagen" src="" alt="Portada del libro">
            </div>
            
            <div class="seccion-info">
                <span id="modal-genero" class="etiqueta-genero"></span>
                <h2 id="modal-titulo"></h2>
                <p id="modal-autor" class="autor-modal"></p>
                <p id="modal-descripcion" class="descripcion-modal"></p>
            </div>
        </div>
    </div>
</div>

<script>
// Función para abrir el modal con los datos del libro
function abrirModal(titulo, autor, genero, descripcion, imagen) {
    document.getElementById('modal-titulo').textContent = titulo;
    document.getElementById('modal-autor').textContent = 'Autor: ' + autor;
    document.getElementById('modal-genero').textContent = genero;
    document.getElementById('modal-descripcion').textContent = descripcion;
    document.getElementById('modal-imagen').src = imagen;
    document.getElementById('modal-libro').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Función para cerrar el modal
function cerrarModal() {
    document.getElementById('modal-libro').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Cerrar modal al hacer clic fuera del contenido
document.getElementById('modal-libro').addEventListener('click', function(event) {
    if (event.target === this) {
        cerrarModal();
    }
});
</script>