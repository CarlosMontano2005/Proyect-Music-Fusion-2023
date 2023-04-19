
// Constante para establecer el formulario de registro del primer usuario.
const SIGNUP_FORM = document.getElementById('signup-form');
const USER_API = 'business/dashboard/usuario.php';
const TIPO_USUARIO_API = 'business/dashboard/Tipo_Usuarios.php';
const ESTADO_USUARIO_API = 'business/dashboard/Estado_Usuarios.php';


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
        sweetAlert(1, JSON.message, true, 'index.html');
    } else {
        sweetAlert(2, JSON.exception, false);
    }
});

function openCreate() {
    // Se abre la caja de diálogo que contiene el formulario.
    //SAVE_MODAL.open();
    // Se restauran los elementos del formulario.
    //SAVE_FORM.reset();
    // Se asigna título a la caja de diálogo.
    //MODAL_TITLE.textContent = 'Agregar Usuario';
    // Se habilitan los campos necesarios.
    //document.getElementById('readOne').disabled = false;
   // document.getElementById('id').disabled = true;
   // document.getElementById('clave').disabled = false;
    /*document.getElementById('confirmar').disabled = false;*/
    // Llamada a la función para llenar el select del formulario. Se encuentra en el archivo components.js
    fillSelect(TIPO_USUARIO_API, 'firstuse', 'Tipo_usuario');
    fillSelect(ESTADO_USUARIO_API, 'firstuse', 'Estado_usuario');
    /*campos obligatorios*/
    document.getElementById('id_usuario').required = false;
    document.getElementById('clave_usuario').required = true;
    document.getElementById('nombre_usuario').required = true;
    document.getElementById('apellido_usuario').required = true;
    document.getElementById('correo_usuario').required = true;
    document.getElementById('alias_usuario').required = true;
    document.getElementById('telefono_usuario').required = true;
    document.getElementById('Tipo_usuario').required = true;
    document.getElementById('Estado_usuario').required = true;
    document.getElementById('foto').required = true;
    //console.log("proceso de abrir modal agregar");
}



