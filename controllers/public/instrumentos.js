// Constante para completar la ruta de la API.
const PRODUCTO_API = 'business/public/productos.php';
// Constante tipo objeto para obtener los parámetros disponibles en la URL.
const PARAMS = new URLSearchParams(location.search);
// Constantes para establecer el contenido principal de la página web.
const TITULO = document.getElementById('title');
const PRODUCTOS = document.getElementById('productos');

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    // Se define un objeto con los datos de la categoría seleccionada.
    const FORM = new FormData();
    FORM.append('id_categoria', PARAMS.get('id'));
    // Petición para solicitar los productos de la categoría seleccionada.
    const JSON = await dataFetch(PRODUCTO_API, 'readProductosCategoria', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se inicializa el contenedor de productos.
        PRODUCTOS.innerHTML = '';
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        JSON.dataset.forEach(row => {
            // Se crean y concatenan las tarjetas con los datos de cada producto.
            PRODUCTOS.innerHTML += `
            <div class="box">
                <a href="detail.html?id=${row.id_producto}"><i class="bx bx-shopping-bag bx-left"></i></a>
                
                <i class="bx bx-show-alt"></i>
                <img src="${SERVER_URL}img/productos/${row.imagen_producto}" alt="producto" />
                <h3>${row.nombre_producto}</h3>
                <div class="stars">
                    <!--Valoracion con estrella-->
                    <i class="bx bxs-star"></i>
                    <i class="bx bxs-star"></i>
                    <i class="bx bxs-star"></i>
                    <i class="bx bxs-star"></i>
                    <i class="bx bxs-star-half"></i>
                </div>
                <span>Precio(US$) ${row.precio_producto}</span>
                <a href="detail.html?id=${row.id_producto}" class="btn">Ver Detalle</a>
          </div>
            `;
        });
        // Se asigna como título la categoría de los productos.
        TITULO.textContent = PARAMS.get('nombre');
        // Se inicializa el componente Material Box para que funcione el efecto Lightbox.
        //M.Materialbox.init(document.querySelectorAll('.materialboxed'));
        // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
        //M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    } else {
        // Se presenta un mensaje de error cuando no existen datos para mostrar.
        TITULO.textContent = JSON.exception;
    }
});