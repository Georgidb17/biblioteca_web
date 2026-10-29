// Paso 9 · Validar el formulario en el navegador antes de enviarlo.
// (PHP vuelve a validar: la validación de JavaScript es comodidad, no seguridad)

const formulario = document.querySelector('#form-libro');
const cajaErrores = document.querySelector('#errores');

function mostrarErrores(errores) {
    cajaErrores.innerHTML = '<ul>' + errores.map(error => `<li>${error}</li>`).join('') + '</ul>';
    cajaErrores.hidden = false;
}

formulario.addEventListener('submit', (evento) => {
    const errores = [];
    const titulo = formulario.titulo.value.trim();
    const autor = formulario.autor.value.trim();
    const genero = formulario.genero.value;
    const anio = formulario.anio.value.trim();
    const paginas = formulario.paginas.value.trim();
    const precio = formulario.precio.value.trim();

    if (titulo === '') errores.push('El título es obligatorio.');
    if (autor === '') errores.push('El autor es obligatorio.');
    if (genero === '') errores.push('Elige un género de la lista.');
    if (anio !== '' && !/^\d+$/.test(anio)) errores.push('El año debe ser un número.');
    if (paginas !== '' && !/^\d+$/.test(paginas)) errores.push('Las páginas deben ser un número.');
    if (precio !== '' && isNaN(precio)) errores.push('El precio debe ser un número (usa punto para los decimales).');

    if (errores.length > 0) {
        evento.preventDefault();          // no se envía el formulario
        mostrarErrores(errores);
    }
});
