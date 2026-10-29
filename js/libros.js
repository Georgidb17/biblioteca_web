// Paso 11 · Búsqueda en vivo: cada vez que se escribe, pedimos los libros
// a api.php con fetch y redibujamos la tabla sin recargar la página.

const inputBuscar = document.querySelector('#buscar');
const cuerpoTabla = document.querySelector('#tabla-libros');
const contador = document.querySelector('#contador');

function crearCelda(texto) {
    const td = document.createElement('td');
    td.textContent = texto;               // textContent: nunca interpreta HTML
    return td;
}

function crearFila(libro) {
    const fila = document.createElement('tr');
    const precio = libro.precio.toLocaleString('es-ES', { minimumFractionDigits: 2 }) + ' €';

    fila.append(
        crearCelda(libro.titulo),
        crearCelda(libro.autor),
        crearCelda(libro.genero),
        crearCelda(libro.anio ?? ''),
        crearCelda(precio),
    );

    const estado = document.createElement('td');
    estado.innerHTML = libro.disponible
        ? '<span class="etiqueta ok">Disponible</span>'
        : '<span class="etiqueta no">Prestado</span>';
    fila.append(estado);

    return fila;
}

function pintarLibros(libros) {
    cuerpoTabla.innerHTML = '';
    libros.forEach(libro => cuerpoTabla.append(crearFila(libro)));
    contador.textContent = `${libros.length} libros`;
}

async function buscarLibros() {
    const texto = inputBuscar.value.trim();

    try {
        const respuesta = await fetch(`api.php?accion=libros&q=${encodeURIComponent(texto)}`);
        if (!respuesta.ok) {
            throw new Error(`Error ${respuesta.status}`);
        }
        const datos = await respuesta.json();
        pintarLibros(datos.libros);
    } catch (error) {
        contador.textContent = 'No se han podido cargar los libros.';
    }
}

