// Constante para completar la ruta de la API.
const CATEGORIA_API = 'business/public/categorias.php';
const PRODUCTO_API = 'business/public/productos.php';

// Constante para establecer el contenedor de categorías.
const CATEGORIAS = document.getElementById('categorias');
const A_INICIO = document.getElementById('a_inicio');
const PRODUCTOS = document.getElementById('productos');

// Constante tipo objeto para establecer las opciones del componente Slider.
const OPTIONS = {
    height: 300
}
// Se inicializa el componente Slider para que funcione el carrusel de imágenes.

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    // Petición para obtener las categorías disponibles.
    const JSON = await dataFetch(CATEGORIA_API, 'readAll');
    const JSON2 = await dataFetch(PRODUCTO_API, 'readAll');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se inicializa el contenedor de categorías.
        CATEGORIAS.innerHTML = '';
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        JSON.dataset.forEach(row => {
            // Se establece la página web de destino con los parámetros.
            url = `instrumentos.html?id=${row.id_categoria_producto}&nombre=${row.nombre_categoria}`;
            // Se crean y concatenan las tarjetas con los datos de cada categoría.
            CATEGORIAS.innerHTML += `
            <div class="box">
            <div class="image">
              <img src="${SERVER_URL}img/categorias/${row.imagen_categoria}" alt="imagen-categoria"/>
              <a href="${url}" class="bx bx-shopping-bag bx-left"></a>
            </div>
            <div class="content">
              <div class="stars">
                <i class="bx bxs-star"></i>
                <i class="bx bxs-star"></i>
                <i class="bx bxs-star"></i>
                <i class="bx bxs-star"></i>
                <i class="bx bxs-star-half"></i>
              </div>
              <h3>${row.nombre_categoria}</h3>
              <p>${row.descripcion_categoria}</p>
              <a href="${url}" class="btn">Ver Instrumentos</a>
              <!-- <span class="price">$12.99</span>-->
            </div>
          </div>
            `;
        });

        // Se inicializa el contenedor de productos.
        PRODUCTOS.innerHTML = '';
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        JSON2.dataset.forEach(row => {
            // Se crean y concatenan las tarjetas con los datos de cada producto.
            PRODUCTOS.innerHTML += `
            <div class="box">
          <div class="image">
            <img
              src="${SERVER_URL}img/productos/${row.imagen_producto}"
              alt="imagen-categoria"
            />
            <a href="detail.html?id=${row.id_producto}"class="bx bx-shopping-bag bx-left"></a>
          </div>
          <div class="content">
            <div class="stars">
              <i class="bx bxs-star"></i>
              <i class="bx bxs-star"></i>
              <i class="bx bxs-star"></i>
              <i class="bx bxs-star"></i>
              <i class="bx bxs-star-half"></i>
            </div>
            <h3>${row.nombre_producto}</h3>
            <p>${row.nombre_categoria}</p>
            <a href="detail.html?id=${row.id_producto}" class="btn">Ver Detalle</a>
            <span class="price">Precio(US$) ${row.precio_producto}</span>
          </div>
        </div>
            `;
        });
        // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
       // M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    } else {
        // Se asigna al título del contenido de la excepción cuando no existen datos para mostrar.
        document.getElementById('title').textContent = JSON.exception;
    }
});