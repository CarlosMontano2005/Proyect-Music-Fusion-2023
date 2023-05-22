// Constante para completar la ruta de la API.
const PEDIDO_API = 'business/public/pedidos.php';
// Constante para establecer el formulario de cambiar producto.
const ITEM_FORM = document.getElementById('item-form');
// Constante para establecer el cuerpo de la tabla.
const TBODY_ROWS = document.getElementById('tbody-rows');
// Constante tipo objeto para establecer las opciones del componente Modal.
const OPTIONS = {
    dismissible: false
}
// Constante para establecer el título de la modal.
//const MODAL_TITLE = document.getElementById('modal-title');
// Constante para establecer la modal de guardar.
//const SAVE_MODAL = new bootstrap.Modal(document.getElementById('add-modal'));

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar los productos del carrito de compras.
    readOrderDetail();
});

/*
*   Función para obtener el detalle del carrito de compras.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
async function readOrderDetail() {
    // Petición para obtener los datos del pedido en proceso.
    const JSON = await dataFetch(PEDIDO_API, 'readAllOrdenes');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se inicializa el cuerpo de la tabla.
        TBODY_ROWS.innerHTML = '';
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        JSON.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS.innerHTML += `
            <tr>
                <td>${row.id_pedido}</td>
                <td>${row.estado_pedido}</td>
                <td>${row.fecha_pedido}</td>
                <td>${row.direccion_pedido}</td>
                <td class="td-button">
                    <!--Boton Editar-->
                    <button onclick="location.href='detalles_pedidos.html?id=${row.id_pedido}&direccion=${row.direccion_pedido}&nombre=${row.nombre_cliente}&apellido=${row.apellido_cliente}'" class="button_edit"
                        class="button-modal">
                        ver pedido
                        <i class="bx bxs-package"></i>
                    </button>
                </td>
            </tr>
            `;
        });
        // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
      //  M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    } else {
        sweetAlert(4, JSON.exception, false, 'index.html');
    }
}

