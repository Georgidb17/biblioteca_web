// Paso 9  · Pedir confirmación antes de devolver un libro.
// Paso 11 · Devolver sin recargar la página: fetch + async/await contra api.php.

const formularios = document.querySelectorAll('.form-devolver');
const cajaMensaje = document.querySelector('#mensaje');

function mostrarMensaje(texto) {
    cajaMensaje.textContent = texto;
    cajaMensaje.hidden = false;
}

async function devolver(evento) {
    evento.preventDefault();                 // el formulario no recarga la página
    const formulario = evento.currentTarget;

    if (!confirm('¿Marcar este libro como devuelto?')) {
        return;
    }

    try {
        // FormData coge los campos ocultos del formulario: accion=devolver e id
        const respuesta = await fetch('api.php', {
            method: 'POST',
            body: new FormData(formulario),
        });
        const resultado = await respuesta.json();

        mostrarMensaje(resultado.mensaje ?? resultado.error);   // error: si falló la base de datos

        if (resultado.ok) {
            const fila = formulario.closest('tr');
            fila.querySelector('.devuelto').textContent = resultado.fecha;
            formulario.remove();
        }
    } catch (error) {
        mostrarMensaje('No se ha podido conectar con el servidor.');
    }
}

