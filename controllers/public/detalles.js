// Constantes para completar la ruta de la API.
const PRODUCTO_API = 'business/public/productos.php';
const PEDIDO_API = 'business/public/pedido.php';
// Constante tipo objeto para obtener los parámetros disponibles en la URL.
const PARAMS = new URLSearchParams(location.search);
// Constante para establecer el formulario de agregar un producto al carrito de compras.
const SHOPPING_FORM = document.getElementById('shopping-form');
// Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
//M.Tooltip.init(document.querySelectorAll('.tooltipped'));

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    // Constante tipo objeto con los datos del producto seleccionado.
    const FORM = new FormData();
    FORM.append('id_producto', PARAMS.get('id'));
    // Petición para solicitar los datos del producto seleccionado.
    const JSON = await dataFetch(PRODUCTO_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        // Se colocan los datos en la página web de acuerdo con el producto seleccionado previamente.
        document.getElementById('imagen').src = SERVER_URL.concat('img/productos/', JSON.dataset.imagen_producto);
        document.getElementById('nombre').textContent = JSON.dataset.nombre_producto;
        document.getElementById('descripcion').textContent = JSON.dataset.descripcion;
        document.getElementById('precio').textContent = JSON.dataset.precio_producto;
        document.getElementById('id_producto').value = JSON.dataset.id_producto;
    } else {
        // Se presenta un mensaje de error cuando no existen datos para mostrar.
        document.getElementById('title').textContent = JSON.exception;
        // Se limpia el contenido cuando no hay datos para mostrar.
        document.getElementById('detalle').innerHTML = '';
    }
});

// Método manejador de eventos para cuando se envía el formulario de agregar un producto al carrito.
SHOPPING_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SHOPPING_FORM);
    // Petición para guardar los datos del formulario.
    const JSON = await dataFetch(PEDIDO_API, 'createDetail', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se constata si el cliente ha iniciado sesión.
    if (JSON.status) {
        sweetAlert(1, JSON.message, true, 'cart.html');
    } else if (JSON.session) {
        sweetAlert(2, JSON.exception, false);
    } else {
        sweetAlert(3, JSON.exception, true, 'login.html');
    }
});