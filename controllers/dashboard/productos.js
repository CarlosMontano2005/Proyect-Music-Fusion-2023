// Constante para completar la ruta de la API.
const PRODUCTO_API = 'business/dashboard/Productos.php';
const USUARIO_API = 'business/dashboard/usuario.php';

const MARCA_PRODUCTO_API = 'business/dashboard/Marca_Producto.php';
const CATEGORIA_PRODUCTO_API = 'business/dashboard/Categoria_Producto.php';
const ESTADO_PRODUCTO_API = 'business/dashboard/Estado_Producto.php';
const USUARIO_PRODUCTO_API = 'business/dashboard/Usuario_Producto.php';
// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('search-form');
// Constante para establecer el formulario de guardar.
const SAVE_FORM = document.getElementById('save-form');
// Constante para establecer el título de la modal.
const MODAL_TITLE = document.getElementById('modal-title');
// Constantes para establecer el contenido de la tabla.
const TBODY_ROWS = document.getElementById('tbody-rows');
const RECORDS = document.getElementById('records');
// Constante tipo objeto para establecer las opciones del componente Modal.
const OPTIONS = {
    dismissible: false
}
// Inicialización del componente Modal para que funcionen las cajas de diálogo.
//M.Modal.init(document.querySelectorAll('.modal'), OPTIONS);
//Constante para establecer la modal de guardar.
//const SAVE_MODAL = M.Modal.getInstance(document.getElementById('save-modal'));

const SAVE_MODAL = document.getElementById('add-modal');

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

// Método manejador de eventos para cuando se envía el formulario de guardar.
SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    
    // Se verifica la acción a realizar.
    (document.getElementById('id_producto').value) ? action = 'update' : action = 'create';
    console.log(document.getElementById('id_producto').value);//id que se toma y ver en consola 
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const JSON = await dataFetch(PRODUCTO_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    console.log("proceso submit");
    if (JSON.status) {
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTable();
        // Se cierra la caja de diálogo.
        // Se muestra un mensaje de éxito.
        sweetAlert(1, JSON.message, true);
    } else {
        sweetAlert(2, JSON.exception, false);
    }
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
    const JSON = await dataFetch(PRODUCTO_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se recorre el conjunto de registros fila por fila.
        JSON.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS.innerHTML +=  `
                <tr>
                    <td>${row.id_producto}</td>
                    <td>${row.nombre_producto}</td>
                    <td>${row.nombre_marca}</td>
                    <td>${row.precio_producto}</td>
                    <td>${row.nombre_categoria}</td>
                    <td>${row.descripcion}</td>
                    <td>${row.estado_producto}</td>
                    <td>${row.nombre_usuario}</td>
                    <td><img src="${SERVER_URL}img/people/${row.foto}" height="100"></td>
                    <td class="td-button">
                        <button  onclick="openUpdate(${row.id_producto})" class="button_edit button-modal" type="button" data-bs-toggle="modal"  data-tooltip="Actualizar"
                        data-bs-target="#add-modal"><i class='bx bxs-edit'  style="color: white;"></i></button>
                        <button onclick="openDelete(${row.id_producto})" class="button_delet" data-tooltip="Eliminar"><i class='bx bx-trash'></i></button>
                        <button onclick="openUpdate(${row.id_producto})" class="button_updet"><i class='bx bx-refresh'></i></button>
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
*   Función para preparar el formulario al momento de insertar un registro.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
function openCreate() {
    // Se abre la caja de diálogo que contiene el formulario.
    //SAVE_MODAL.open();
    // Se restauran los elementos del formulario.
    SAVE_FORM.reset();
    // Se asigna título a la caja de diálogo.
    MODAL_TITLE.textContent = 'Agregar Producto';
    // Se habilitan los campos necesarios.
    //document.getElementById('readOne').disabled = false;
   // document.getElementById('id').disabled = true;
   // document.getElementById('clave').disabled = false;
    /*document.getElementById('confirmar').disabled = false;*/
    // Llamada a la función para llenar el select del formulario. Se encuentra en el archivo components.js
    fillSelect(MARCA_PRODUCTO_API, 'readAll', 'Marca_Producto');
    fillSelect(CATEGORIA_PRODUCTO_API, 'readAll', 'categoria');
    fillSelect(ESTADO_PRODUCTO_API, 'readAll', 'id_estado_producto');
    fillSelect(USUARIO_PRODUCTO_API, 'readAll', 'usuario');
    /*campos obligatorios*/
    document.getElementById('id_producto').required = false;
    document.getElementById('nombre_producto').required = true;
    document.getElementById('Marca_Producto').required = true;
    document.getElementById('precio_producto').required = true;
    document.getElementById('categoria').required = true;
    document.getElementById('descripcion').required = true;
    document.getElementById('id_estado_producto').required = true;
    document.getElementById('usuario').required = true;
    document.getElementById('foto').required = true;
    console.log("proceso de abrir modal agregar");
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
async function openUpdate(id) {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_producto', id);
    // Petición para obtener los datos del registro solicitado.
    const JSON = await dataFetch(PRODUCTO_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se abre la caja de diálogo que contiene el formulario.
       // SAVE_MODAL.open();
        // Se restauran los elementos del formulario.
        SAVE_FORM.reset();
        // Se asigna título a la caja de diálogo.
        MODAL_TITLE.textContent = 'Actualizar Producto';
        // Se deshabilitan los campos necesarios.
     //  document.getElementById('id').disabled = true;
      // document.getElementById('clave').disabled = true;
       /*  document.getElementById('clave').disabled = true;
    
        document.getElementById('confirmar').disabled = true;*/
        // Se inicializan los campos del formulario.
        document.getElementById('id_producto').value = JSON.dataset.id_producto;
        document.getElementById('nombre_producto').value = JSON.dataset.nombre_producto;
        document.getElementById('precio_producto').value = JSON.dataset.precio_producto;
        document.getElementById('descripcion').value = JSON.dataset.descripcion;
        fillSelect(MARCA_PRODUCTO_API, 'readAll', 'Marca_Producto', JSON.dataset.id_marca_producto);
        fillSelect(CATEGORIA_PRODUCTO_API, 'readAll', 'categoria', JSON.dataset.id_categoria_producto);
        fillSelect(ESTADO_PRODUCTO_API, 'readAll', 'id_estado_producto', JSON.dataset.id_estado_producto);
        fillSelect(USUARIO_PRODUCTO_API, 'readAll', 'usuario', JSON.dataset.id_usuario);
        document.getElementById('foto').required = false;
        console.log("proceso abri caja modal de actualizar");//mensaje 
        // Se actualizan los campos para que las etiquetas (labels) no queden sobre los datos.
        //M.updateTextFields();
    } else {
        sweetAlert(2, JSON.exception, false);
    }
}

/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
async function openDelete(id) {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar el producto de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('id_producto', id);
        // Petición para eliminar el registro seleccionado.
        const JSON = await dataFetch(PRODUCTO_API , 'delete', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (JSON.status) {
            // Se carga nuevamente la tabla para visualizar los cambios.
            fillTable();
            // Se muestra un mensaje de éxito.
            sweetAlert(1, JSON.message, true);
        } else {
            sweetAlert(2, JSON.exception, false);
        }
    }
}

function openReport(id) {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reports/dashboard/usuario.php`);
    // Se agrega un parámetro a la ruta con el valor del registro seleccionado.
    PATH.searchParams.append('id_usuario', id);
    // Se abre el reporte en una nueva pestaña del navegador web.
    window.open(PATH.href);
}