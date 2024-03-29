// Constante para completar la ruta de la API.
const CLIENTE_API = 'business/dashboard/Clientes.php';
const PRODUCTO_API = 'business/dashboard/Productos.php';

const GENERO_API = 'business/dashboard/Genero.php';
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
const SAVE_MODAL = new bootstrap.Modal(document.getElementById('add-modal'));

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
    graficoLineasClientesGeneros();
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
    (document.getElementById('id').value) ? action = 'update' : action = 'create';
    console.log(document.getElementById('id').value);//id que se toma y ver en consola 
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const JSON = await dataFetch(CLIENTE_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    console.log("proceso submit");
    if (JSON.status) {
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTable();
        // Se cierra la caja de diálogo.
        SAVE_MODAL.hide();
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
    const JSON = await dataFetch(CLIENTE_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se recorre el conjunto de registros fila por fila.
        JSON.dataset.forEach(row => {
            (row.estado) ? estado = 'bx bxs-check-square' : estado = 'bx bx-checkbox-minus';
           
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS.innerHTML +=  `
                <tr>
                    <td>${row.id_cliente}</td>
                    <td>${row.nombre_cliente}</td>
                    <td>${row.apellido_cliente}</td>
                    <td>${row.correo_cliente}</td>
                    <td>${row.fecha_nacimiento}</td>
                    <td>${row.genero}</td>
                    <td>${row.telefono_cliente}</td>
                    <td>${row.dui}</td>
                    <td>    
                        <label class="container-cheacked">
                        <input type="checkbox" checked="checked" id="checkedId"  class="checkedId">
                        <div class="checkmark"></div>
                        </label>
                        ${row.estado}
                    </td>
                    <td>${row.direccion_cliente}</td>
                    <td class="td-button">
                        <button  onclick="openUpdate(${row.id_cliente})" class="button_edit button-modal" type="button" data-bs-toggle="modal"  data-tooltip="Actualizar"
                        data-bs-target="#add-modal"><i class='bx bxs-edit'  style="color: white;"></i></button>
                        <button onclick="openDelete(${row.id_cliente})" class="button_delet" data-tooltip="Eliminar"><i class='bx bx-trash'></i></button>
                        <button onclick="openUpdate(${row.id_cliente})" class="button_updet"><i class='bx bx-refresh'></i></button>
                    </td>
                </tr>
                `
                ;
                (row.estado) ?  document.getElementById("checkedId").checked = true : document.getElementById("checkedId").checked  = false;
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
    MODAL_TITLE.textContent = 'Agregar cliente';
    // Se habilitan los campos necesarios.
    //document.getElementById('readOne').disabled = false;
   // document.getElementById('id').disabled = true;
   // document.getElementById('clave').disabled = false;
    /*document.getElementById('confirmar').disabled = false;*/
    // Llamada a la función para llenar el select del formulario. Se encuentra en el archivo components.js
    fillSelect(GENERO_API, 'readAll', 'generos');
    /*campos obligatorios*/
    document.getElementById('id').required = false;
    document.getElementById('clave').required = true;
    document.getElementById('nombres').required = true;
    document.getElementById('apellidos').required = true;
    document.getElementById('correo').required = true;
    document.getElementById('nacimiento').required = true;
    document.getElementById('generos').required = true;
    document.getElementById('telefono').required = true;
    document.getElementById('direccion').required = true;
    document.getElementById('estado').required = true;
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
    FORM.append('id', id);
    // Petición para obtener los datos del registro solicitado.
    const JSON = await dataFetch(CLIENTE_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se abre la caja de diálogo que contiene el formulario.
       // SAVE_MODAL.open();
        // Se restauran los elementos del formulario.
        SAVE_FORM.reset();
        // Se asigna título a la caja de diálogo.
        MODAL_TITLE.textContent = 'Actualizar Cliente';
        // Se deshabilitan los campos necesarios.
     //  document.getElementById('id').disabled = true;
      // document.getElementById('clave').disabled = true;
       /*  document.getElementById('clave').disabled = true;
    
        document.getElementById('confirmar').disabled = true;*/
        // Se inicializan los campos del formulario.
        document.getElementById('id').value = JSON.dataset.id_cliente;
        fillSelect(GENERO_API, 'readAll', 'generos', JSON.dataset.id_genero);
        document.getElementById('nombres').value = JSON.dataset.nombre_cliente;
        document.getElementById('apellidos').value = JSON.dataset.apellido_cliente;
        document.getElementById('correo').value = JSON.dataset.correo_cliente;
        document.getElementById('nacimiento').value = JSON.dataset.fecha_nacimiento;
        document.getElementById('dui').value = JSON.dataset.dui;
        document.getElementById('clave').value = JSON.dataset.clave
        
        document.getElementById('telefono').value = JSON.dataset.telefono_cliente;
        document.getElementById('direccion').value = JSON.dataset.direccion_cliente;
        if (JSON.dataset.estado) {
            document.getElementById('estado').checked = true;
        } else {
            document.getElementById('estado').checked = false;
        }
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
    const RESPONSE = await confirmAction('¿Desea eliminar el cliente de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('id_cliente', id);
        // Petición para eliminar el registro seleccionado.
        const JSON = await dataFetch(CLIENTE_API, 'delete', FORM);
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
/*
//hora y saludo
// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Se define un objeto con la fecha y hora actual.
    let today = new Date();
    // Se define una variable con el número de horas transcurridas en el día.
    let hour = today.getHours();
    // Se define una variable para guardar un saludo.
    let greeting = '';
    // Dependiendo del número de horas transcurridas en el día, se asigna un saludo para el usuario.
    if (hour < 12) {
        greeting = 'Buenos días';
    } else if (hour < 19) {
        greeting = 'Buenas tardes';
    } else if (hour <= 23) {
        greeting = 'Buenas noches';
    }
    // Se muestra un saludo en la página web.
    document.getElementById('greeting').textContent = greeting;
    // Se llaman a la funciones que generan los gráficos en la página web.
    //graficoBarrasCategorias();
    //graficoPastelCategorias();
});
*/

//#region reporte  de dato personal
/*
 *   Función para abrir el reporte de productos por categoría.
 *   Parámetros: ninguno.W
 *   Retorno: ninguno.
 */
function openReport() {
	// Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
	const PATH = new URL(`${SERVER_URL}reports/dashboard/cliente_reporte.php`);
	// Se abre el reporte en una nueva pestaña del navegador web.
	window.open(PATH.href);
}
//#endregion

//#region grafica
/*
*   Función asíncrona para mostrar en un gráfico de lineas la cantidad de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
async function graficoLineasClientesGeneros() {
    // Petición para obtener los datos del gráfico.
    const JSON = await dataFetch(PRODUCTO_API, 'CantidadProductosVendidos');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (JSON.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let genero = [];
        let porcentajes = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        JSON.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            genero.push(row.genero);
            porcentajes.push(row.porcentaje);
        });
        // Llamada a la función que genera y muestra un gráfico de lineas. Se encuentra en el archivo components.js
        PolarArearaph('ChartLinea', genero, porcentajes, 'Cantidad de Genero', 'Cantidad de clientes por genero');
    } else {
        document.getElementById('ChartLinea').remove();
        console.log(JSON.exception);
    }
}
//#endregion