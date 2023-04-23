// Constante para completar la ruta de la API.
const DET_PEDIDOS_API = 'business/dashboard/detalles_pedidos.php';
const VALORACION_API = 'business/dashboard/detalles_Valoraciones.php';
const PRODUCTO = 'business/dashboard/Productos.php';

// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('search-form');
// Constante para establecer el formulario de guardar.
const SAVE_FORM = document.getElementById('save-form');
// Constante para establecer el título de la modal.
const MODAL_TITLE = document.getElementById('modal-title');
// Constantes para establecer el contenido de la tabla.
const TBODY_ROWS = document.getElementById('tbody-rows');
const RECORDS = document.getElementById('records');

// Constante para establecer la modal de guardar.
//const SAVE_MODAL = new bootstrap.Modal(document.getElementById('add-modal-detalle'));
const SAVE_MODAL = new bootstrap.Modal(document.getElementById('add-modal-detalle'));

/*//////////// 
const myModal = document.getElementById('myModal')
const myInput = document.getElementById('myInput')

myModal.addEventListener('shown.bs.modal', () => {
  myInput.focus()
})
//////////// */

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para llenar la tabla con los registros disponibles.
    fillTable();
});

// Método manejador de eventos para cuando se envía el formulario de buscar.
SEARCH_FORM.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SEARCH_FORM);
    // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
    fillTable(FORM);
});


/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
async function fillTable(form = null) {
    // Se inicializa el contenido de la tabla.
    TBODY_ROWS.innerHTML = '';
    RECORDS.textContent = '';
    // Se verifica la acción a realizar.
    (form) ? action = 'search' : action = 'readAll';
    // Petición para obtener los registros disponibles.
    const JSON = await dataFetch(DET_PEDIDOS_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se recorre el conjunto de registros fila por fila.
        JSON.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS.innerHTML +=  `
                <tr>
                    <td>${row.id_detalle_pedido}</td>
                    <td>${row.id_pedido}</td>
                    <td>${row.nombre_producto}</td>
                    <td>${row.cantidad_producto}</td>
                    <td>${row.precio_detalle_producto}</td>
                    <td class="td-button">
                        <button onclick="openUpdate(${row.id_detalle_pedido})"  class="button_updet" data-bs-toggle="modal" data-bs-target="#add-modal-detalle" ><i class='bx bx-edit' ></i></button>
                    </td>
                </tr>
                `;
        });
        // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
       //M.Tooltip.init(document.querySelectorAll('.tooltipped'));
        // Se muestra un mensaje de acuerdo con el resultado.
        RECORDS.textContent = JSON.message;
    } else {
        sweetAlert(4, JSON.exception, true);
    }
}


/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
async function openUpdate(id) {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id', id);
    // Petición para obtener los datos del registro solicitado.
    const JSON = await dataFetch(DET_PEDIDOS_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se abre la caja de diálogo que contiene el formulario.
       // SAVE_MODAL.open();
        // Se restauran los elementos del formulario.
       // SAVE_FORM.reset();
        // Se asigna título a la caja de diálogo.
        MODAL_TITLE.textContent = 'Valoracion del detalle del Pedidos';
        // Se deshabilitan los campos necesarios.
     //  document.getElementById('id').disabled = true;
      // document.getElementById('clave').disabled = true;
       /*  document.getElementById('clave').disabled = true;
    
        document.getElementById('confirmar').disabled = true;*/
        // Se inicializan los campos del formulario.
        document.getElementById('id_detalle_pedido').value = JSON.dataset.id_detalle_pedido;
        document.getElementById('id_pedido').value = JSON.dataset.id_pedido;
        document.getElementById('cantidad').value = JSON.dataset.cantidad_producto;
        document.getElementById('precio_detalle').value = JSON.dataset.precio_detalle_producto;
        fillSelect(PRODUCTO, 'readAll', 'id_Producto', JSON.dataset.id_producto);
        console.log("proceso abri caja modal de actualizar");//mensaje 
        // Se actualizan los campos para que las etiquetas (labels) no queden sobre los datos.
        //M.updateTextFields();
 /*
        document.getElementById('id_valoracion').value = JSON.dataset.id;
        document.getElementById('id_detalle').value = JSON.dataset.Id_detalle_pedido;
        document.getElementById('calificacion').value = JSON.dataset.calificacion_producto;
        document.getElementById('comentario').value = JSON.dataset.comentario_producto;
        document.getElementById('fecha_comentario').value = JSON.dataset.fecha_comentario;
        if (JSON.dataset.estado_comentario) {
            document.getElementById('estado_comentario').checked = true;
        } else {
            document.getElementById('estado_comentario').checked = false;
        }*/
    } else {
        sweetAlert(2, JSON.exception, false);
    }
}

/*
*   Función para abrir el reporte del detalle del pedido de una categoría.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
function openReport(id) {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/dashboard/productos_categoria.php`);
    // Se agrega un parámetro a la ruta con el valor del registro seleccionado.
    PATH.searchParams.append('id_categoria', id);
    // Se abre el reporte en una nueva pestaña del navegador web.
    window.open(PATH.href);
}