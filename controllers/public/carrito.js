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
const MODAL_TITLE = document.getElementById('modal-title');
// Constante para establecer la modal de guardar.
const SAVE_MODAL = new bootstrap.Modal(document.getElementById('add-modal'));

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar los productos del carrito de compras.
    readOrderDetail();
});

// Método manejador de eventos para cuando se envía el formulario de cambiar cantidad de producto.
ITEM_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(ITEM_FORM);
    let existencia = 0;
    //validar si es una cantidad de existencia en los productos por que que esta agregando al carrito
    const JSON = await dataFetch(PEDIDO_API, 'varlidarExistencia', FORM);
    if (JSON.status) {
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se constata si el cliente ha iniciado sesión.
        if (JSON.status) {
            //Se verifica si la cantidad es menor a 0 y no sobrepasa la existencia
            
            existencia = JSON.dataset.resta_existencia;
            console.log('Existencia producto: ' +existencia);
            //se validad la cantidad total si es mayor a 0 se puede ralizar el pedido   
            //si es mayor a 0 significa que sobra existencia para seguir comprando          
            if(existencia>0){
                //calcaulos para el total de la cantidad
                let idprodcto= 0;
                idprodcto =  document.getElementById('id_producto').value;
                console.log('id producto '+ idprodcto);
                let cantidadnueva = 0;
                let cantidadactualizar = 0;
                //verificar cantidad actual mas la nueva
                cantidadnueva = document.getElementById('cantidad').value;
                console.log('actual '+cantidadactual);
                console.log('nueva '+cantidadnueva);
                //si la cantidad actual es mayor a la cantidad nueva se la existencia en los productos caso contrario si es menor
                if(cantidadnueva > cantidadactual){
                    //lo que sobra se le resta la cantidad de existencia ya que se le resto lo que se aumento 
                    //resta para ver cuanto se le añadio o se le quito
                    cantidadactualizar = cantidadnueva-cantidadactual;
                    console.log('cantidad actualizada si se aumenta '+ cantidadactualizar);
                    document.getElementById('cantida_resta').value = cantidadactualizar;
                    const FORM = new FormData(ITEM_FORM);
                    // Petición para actualizar la cantidad de producto.
                    const JSON2 = await dataFetch(PEDIDO_API, 'updateExistenciaResta', FORM);
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                    if (JSON2.status) {
                        sweetAlert(1, JSON2.message, true);
                        const JSON3 = await dataFetch(PEDIDO_API, 'updateDetail', FORM);
                         if(JSON3.status){
                            // Se actualiza la tabla para visualizar los cambios.
                            readOrderDetail();
                            // Se cierra la caja de diálogo del formulario.
                            //ITEM_MODAL.close();
                            SAVE_MODAL.hide();
                            // Se muestra un mensaje de éxito.
                            sweetAlert(1, JSON3.message, true);
                         }
                         else{
                            sweetAlert(2, JSON3.exception, false);
                         }
                        // Se muestra un mensaje de éxito.
                        sweetAlert(1, JSON2.message, true);
                    } else {
                        sweetAlert(2, JSON2.exception, false);
                    }
                }
                else if(cantidadnueva < cantidadactual){
                    //si la cantidad nueva es menor se le resta a la cantidad actual 
                    // lo que sobre se le sumara a la existencia del producto
                    cantidadactualizar = cantidadactual - cantidadnueva;
                    console.log('cantidad actualizada si se disminuye '+cantidadactualizar);
                    document.getElementById('cantida_resta').value = cantidadactualizar;
                    const FORM = new FormData(ITEM_FORM);
                    // Petición para actualizar la cantidad de producto.
                    const JSON2 = await dataFetch(PEDIDO_API, 'updateExistenciaSuma', FORM);
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                    if (JSON2.status) {
                        sweetAlert(1, JSON2.message, true);
                        const JSON3 = await dataFetch(PEDIDO_API, 'updateDetail', FORM);
                         if(JSON3.status){
                            // Se actualiza la tabla para visualizar los cambios.
                            readOrderDetail();
                            // Se cierra la caja de diálogo del formulario.
                            //ITEM_MODAL.close();
                            SAVE_MODAL.hide();
                            // Se muestra un mensaje de éxito.
                            sweetAlert(1, JSON3.message, true);
                         }
                         else{
                            sweetAlert(2, JSON3.exception, false);
                         }
                        // Se muestra un mensaje de éxito.
                        sweetAlert(1, JSON2.message, true);
                    } else {
                        sweetAlert(2, JSON2.exception, false);
                    }
                }else if(cantidadnueva == cantidadactual){
                    cantidadactualizar = cantidadactual;
                    console.log('cantidades igual que la nueva ' + cantidadactualizar);
                }
            }
            else{
                sweetAlert(2, 'Lo Sentimos, no tenemos tanta cantidad de este producto, por favor escriba una cantidad menor a la que pide ', false);
            }
        }
    } else if (JSON.session) {
        sweetAlert(2, JSON.exception, false);
    } 

    
    
});

/*
*   Función para obtener el detalle del carrito de compras.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
async function readOrderDetail() {
    // Petición para obtener los datos del pedido en proceso.
    const JSON = await dataFetch(PEDIDO_API, 'readOrderDetail');
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
                        <td class="contenedor-galeria img-td" >
                        <img class="img-galeria"   id="imagen" src="${SERVER_URL}img/productos/${row.imagen_producto}">
                        </td>
                        <td>${row.nombre_producto}</td>
                        <td>${row.precio_detalle_producto}</td>
                        <td class="td-contenedor-contador">
                        ${row.cantidad_detalle_producto}
                        </td>
                        <td>${subtotal.toFixed(2)}</td>
                        <td class="td-button">
                        
                        <!--Boton Editar-->
                        <button onclick="openUpdate(${row.id_detalle_pedido}, ${row.cantidad_detalle_producto},${row.id_producto})" class="button_edit" class="button-modal" data-bs-toggle="modal"
                            data-bs-target="#add-modal"  data-tooltip="Actualizar"><i class='bx bxs-edit-alt'></i></button>
                        <!--Boton Eliminar-->
                        <button onclick="openDelete(${row.id_detalle_pedido})" class="button_delet" data-tooltip="Eliminar"><i class='bx bx-trash'></i></button>
                        </td>
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
*   Función para abrir la caja de diálogo con el formulario de cambiar cantidad de producto.
*   Parámetros: id (identificador del producto) y quantity (cantidad actual del producto).
*   Retorno: ninguno.
*/
let cantidadactual;
function openUpdate(id, quantity, id_producto) {
    // Se abre la caja de diálogo que contiene el formulario.
    //ITEM_MODAL.open();
    // Se inicializan los campos del formulario con los datos del registro seleccionado.
    MODAL_TITLE.textContent = 'Cambiar Cantidad';
    cantidadactual = document.getElementById('cantidad').value = quantity;
    document.getElementById('id_detalle').value = id;
    document.getElementById('id_producto').value = id_producto;
    document.getElementById('cantida_resta').value = 0;
    // Se actualizan los campos para que las etiquetas (labels) no queden sobre los datos.
   // M.updateTextFields();
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

/*
*   Función asíncrona para mostrar un mensaje de confirmación al momento de eliminar un producto del carrito.
*   Parámetros: id (identificador del producto).
*   Retorno: ninguno.
*/
async function openDelete(id) {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Está seguro de remover el producto?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define un objeto con los datos del producto seleccionado.
        const FORM = new FormData();
        FORM.append('id_detalle', id);
        // Petición para eliminar un producto del carrito de compras.
        const JSON = await dataFetch(PEDIDO_API, 'deleteDetail', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (JSON.status) {
            // Se carga nuevamente la tabla para visualizar los cambios.
            readOrderDetail();
            sweetAlert(1, JSON.message, true);
        } else {
            sweetAlert(2, JSON.exception, false);
        }
    }
}