// Constante para completar la ruta de la API.
const PEDIDOS_API = 'business/dashboard/Pedidos.php';

const CLIENTES_API = 'business/dashboard/Clientes.php';
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
    const JSON = await dataFetch(PEDIDOS_API, action, FORM);
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
    const JSON = await dataFetch(PEDIDOS_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se recorre el conjunto de registros fila por fila.
        JSON.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS.innerHTML +=  `
                <tr>
                    <td>${row.id_pedido}</td>
                    <td>${row.estado_pedido}</td>
                    <td>${row.fecha_pedido}</td>
                    <td>${row.direccion_pedido}</td>
                    <td>${row.nombre_cliente}</td>
                    <td class="td-button">
                        <button  onclick="openUpdate(${row.id_pedido})" class="button_edit button-modal" type="button" data-bs-toggle="modal"  data-tooltip="Actualizar"
                        data-bs-target="#add-modal"><i class='bx bxs-edit'  style="color: white;"></i></button>
                        <button onclick="openUpdate(${row.id_pedido})" class="button_updet"><i class='bx bx-refresh'></i></button>
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
    MODAL_TITLE.textContent = 'Agregar cliente';
    // Se habilitan los campos necesarios.
    //document.getElementById('readOne').disabled = false;
   // document.getElementById('id').disabled = true;
   // document.getElementById('clave').disabled = false;
    /*document.getElementById('confirmar').disabled = false;*/
    // Llamada a la función para llenar el select del formulario. Se encuentra en el archivo components.js
    fillSelect(CLIENTES_API, 'readAll', 'generos');
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
    const JSON = await dataFetch(PEDIDOS_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se abre la caja de diálogo que contiene el formulario.
       // SAVE_MODAL.open();
        // Se restauran los elementos del formulario.
        SAVE_FORM.reset();
        // Se asigna título a la caja de diálogo.
        MODAL_TITLE.textContent = 'Actualizar de pedido';
        // Se deshabilitan los campos ne    cesarios.
     //  document.getElementById('id').disabled = true;
      // document.getElementById('clave').disabled = true;
       /*  document.getElementById('clave').disabled = true;
    
        document.getElementById('confirmar').disabled = true;*/
        // Se inicializan los campos del formulario.

        document.getElementById('id').value = JSON.dataset.id_pedido;
        fillSelect(PEDIDOS_API, 'readAllClientes', 'cliente', JSON.dataset.id_cliente);
        fillSelect(PEDIDOS_API, 'readAllEstadoPedido', 'estado_pedido', JSON.dataset.id_estado_usuario);
        document.getElementById('direccion').value = JSON.dataset.direccion_pedido;
        document.getElementById('fecha_pedido').value = JSON.dataset.fecha_pedido;
        
        
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
        const JSON = await dataFetch(PEDIDOS_API, 'delete', FORM);
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

function openReport() {
	// Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
	const PATH = new URL(`${SERVER_URL}reports/dashboard/pedido_reporte.php`);
	// Se abre el reporte en una nueva pestaña del navegador web.
	window.open(PATH.href);
}

async function graficoPastelPedidos() {
    // Petición para obtener los datos del gráfico.
    const JSON = await dataFetch(PEDIDOS_API, 'porcentajePedidosEstado');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (JSON.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let estado = [];
        let porcentajes = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        JSON.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            estado.push(row.estado_pedido);
            porcentajes.push(row.porcentaje);
        });
        // Llamada a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
        pieGraph('ChartPie', estado, porcentajes, 'Cantidad de estados', 'Cantidad de pedidos por estado del pedido');
    } else {
        document.getElementById('ChartPie').remove();
        console.log(JSON.exception);
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