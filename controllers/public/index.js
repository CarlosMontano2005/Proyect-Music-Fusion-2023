// Constante para completar la ruta de la API.
const CATEGORIA_API = 'business/public/categorias.php';
const PRODUCTO_API = 'business/public/productos.php';

// Constante para establecer el contenedor de categorías.
const CATEGORIAS = document.getElementById('categorias');
//CONSTANTE DE BOTON inicio
const A_INICIO = document.getElementById('a_inicio');
//constante de producto id
const PRODUCTOS = document.getElementById('productos');
//constante de los elementos de la card id de otros comentarios
const CARD_OTROS_COMENTARIOS = document.getElementById('card_otros_comentario');
//constante de card de comentario propio
const CARD_COMENTARIO_PROPIO = document.getElementById('card_comentario_propio');
// Constante para establecer el formulario de guardar.
const SAVE_FORM_COMENTARIO = document.getElementById('form-comentario');
// Constante para establecer el título de la modal.
const MODAL_TITLE = document.getElementById('modal-title');
// Constante para establecer la modal de guardar.
const SAVE_MODAL = new bootstrap.Modal(document.getElementById('modal-valoraciones'));

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
          <a href="#" class="bx bx-show-alt bx-left"  data-bs-toggle="modal" onclick="openComentario(${row.id_producto})"
          data-bs-target="#modal-valoraciones"></a>
            <img
            src="${SERVER_URL}img/productos/${row.imagen_producto}"
              alt="imagen-categoria"
            />
            <a href="detalles.html?id=${row.id_producto}" class="bx bx-shopping-bag bx-left"></a>
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
            <a href="detalles.html?id=${row.id_producto}"  class="btn">Ver Detalle</a>
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


/**
*metodo del comentario
*/
/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
async function openComentario(id) {
  // Se define una constante tipo objeto con los datos del registro seleccionado.
  const FORM = new FormData();
  FORM.append('id_producto', id);
  console.log(FORM.append('id_producto', id));
  // Petición para obtener los datos del registro solicitado.
  const JSON = await dataFetch(PRODUCTO_API, 'readAllComentarios', FORM);
  // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
  if (JSON.status) {
    // Se abre la caja de diálogo que contiene el formulario.
    // SAVE_MODAL.open();
    // Se restauran los elementos del formulario.
    SAVE_FORM_COMENTARIO.reset();
    // Se asigna título a la caja de diálogo.
    MODAL_TITLE.textContent = 'Leer Comentarios';
    // Se inicializa el contenedor de categorías.
    CARD_OTROS_COMENTARIOS.innerHTML = '';
    // Se recorre el conjunto de registros fila por fila a través del objeto row.
    JSON.dataset.forEach(row => {CARD_OTROS_COMENTARIOS.innerHTML += `
    <div class="card mb-4">
      <p id="nombre_otros_comentario">${row.cliente}</p>
      <p id="fecha_otros_comentario">${row.fecha_comentario}</p>
      <p id="otros_comentario">${row.comentario_producto}.</p>
    </div>
      `;
    });
    // Se actualizan los campos para que las etiquetas (labels) no queden sobre los datos.
    //M.updateTextFields();
  } else {
      sweetAlert(2, JSON.exception, false);
  }
}