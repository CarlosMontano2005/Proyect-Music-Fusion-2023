
// Constante para establecer el formulario de registro del primer usuario.
const SIGNUP_FORM = document.getElementById('signup-form');
const USER_API = 'business/dashboard/usuario.php';
/*const TIPO_USUARIO_API = 'business/dashboard/Tipo_Usuarios.php';
const ESTADO_USUARIO_API = 'business/dashboard/Estado_Usuarios.php';

fillSelect(TIPO_USUARIO_API, 'readAll', 'Tipo_usuario');
fillSelect(ESTADO_USUARIO_API, 'readAll', 'Estado_usuario');*/

// Método manejador de eventos para cuando se envía el formulario de registro del primer usuario.
SIGNUP_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SIGNUP_FORM);
    // Petición para registrar el primer usuario del sitio privado.
    const JSON = await dataFetch(USER_API, 'signup', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
    //    sweetAlert(1, JSON.message, true, 'crear_cuenta.html');
    } else {
        sweetAlert(2, JSON.exception, false);
    }
});



