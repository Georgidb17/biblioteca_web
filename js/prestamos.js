// Paso 9 · Pedir confirmación antes de devolver un libro.

const formularios = document.querySelectorAll('.form-devolver');

formularios.forEach(formulario => {
    formulario.addEventListener('submit', (evento) => {
        if (!confirm('¿Marcar este libro como devuelto?')) {
            evento.preventDefault();      // si pulsa Cancelar, no se envía
        }
    });
});
