// Constante para completar la ruta de la API.
const PEDIDO_API = 'business/public/pedidos.php';
// Constante para establecer el formulario de cambiar producto.
const ITEM_FORM = document.getElementById('item-form');
// Constante para establecer el cuerpo de la tabla.
const TBODY_ROWS = document.getElementById('tbody-rows');
// Constante tipo objeto para obtener los parámetros disponibles en la URL.
const PARAMS = new URLSearchParams(location.search);
// Constante para establecer el formulario de agregar un producto al carrito de compras.
const SHOPPING_FORM = document.getElementById('detalle-form');
// Constante tipo objeto para establecer las opciones del componente Modal.

const OPTIONS = {
    dismissible: false
}
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
    // Constante tipo objeto con los datos del producto seleccionado.
    const FORM = new FormData();
    //agarrar el formulado
    FORM.append('id_pedido', PARAMS.get('id'));
    //
    let idpedido = PARAMS.get('id');
    document.getElementById('id_pedido').textContent = idpedido;
    document.getElementById('nombre').textContent = PARAMS.get('nombre');
    document.getElementById('apellido').textContent = PARAMS.get('apellido');
    document.getElementById('direccion').textContent = PARAMS.get('direccion');
    const JSON = await dataFetch(PEDIDO_API, 'readOrderDetailOrdenes', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se inicializa el cuerpo de la tabla.
        TBODY_ROWS.innerHTML = '';
        // Se declara e inicializa una variable para calcular el importe por cada producto.
        let subtotal = 0;
        // Se declara e inicializa una variable para sumar cada subtotal y obtener el monto final a pagar.
        let total = 0;
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        JSON.dataset.forEach(row => {
            subtotal = row.precio_detalle_producto * row.cantidad_detalle_producto;
            total += subtotal;
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TBODY_ROWS.innerHTML += `
                    <tr>
                        <td>${row.fila}</td>
                        <td class="contenedor-galeria img-td">
                            <img class="img-galeria" id="imagen" src="${SERVER_URL}img/productos/${row.imagen_producto}" />
                        </td>
                        <td>${row.nombre_producto}</td>
                        <td>${row.precio_detalle_producto}</td>
                        <td class="td-contenedor-contador">
                            ${row.cantidad_detalle_producto}
                        </td>
                        <td>${subtotal.toFixed(2)}</td>
                    </tr>
            `;
        });
        // Se muestra el total a pagar con dos decimales.
        document.getElementById('pago').textContent = total.toFixed(2);
        // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
      //  M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    } else {
        sweetAlert(4, JSON.exception, false, 'index.html');
    }
}


/*
*   Función asíncrona para mostrar un mensaje de confirmación al momento de finalizar el pedido.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
async function finishOrder() {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Está seguro de finalizar el pedido?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Petición para finalizar el pedido en proceso.
        const JSON = await dataFetch(PEDIDO_API, 'finishOrder');
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (JSON.status) {
            sweetAlert(1, JSON.message, true, 'index.html');

        } else {
            sweetAlert(2, JSON.exception, false);
        }
    }
}

