
// Constante para establecer el formulario de registro del primer usuario.
const SIGNUP_FORM = document.getElementById('signup-form');
// Constante para establecer el formulario de inicio de sesión.
const LOGIN_FORM = document.getElementById('login-form');

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    // Petición para consultar los usuarios registrados.
    const JSON = await dataFetch(USER_API, 'readUsers');
    // Se comprueba si existe una sesión, de lo contrario se sigue con el flujo normal.
    if (JSON.session) {
        // Se direcciona a la página web de bienvenida.
        location.href = 'dashboard.html';
    } else if (JSON.status) {
        // Se muestra el formulario para iniciar sesión.
        sweetAlert(4, JSON.message, false);
    } else {
        // Se muestra el formulario para registrar el primer usuario.
        sweetAlert(4, JSON.exception, false, 'crear_cuenta.html');
    }
});


// Método manejador de eventos para cuando se envía el formulario de inicio de sesión.
LOGIN_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(LOGIN_FORM);
    // Petición para iniciar sesión.v
    const JSON = await dataFetch(USER_API, 'login', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (JSON.status) {
        sweetAlert(1, JSON.message, true, 'dashboard.html');
    } else {
        sweetAlert(2, JSON.exception, false);
    }
});
//comando para ver contraseñas 
//id del boton ver clave
verClave = document.getElementById("ver-clave");
//campo de la clave 
InputClaveText = document.getElementById("clave");
checklabel = document.querySelector(".form-check-label");

verClave.addEventListener("click", function() {
    if(InputClaveText.type == "password")
    {
        InputClaveText.type = "text";
        checklabel.innerText = "Ocultar Clave";
    }
    else{
        InputClaveText.type = "password";
        checklabel.innerText = "Monstrar Clave";
    }
});

//comando para ver mensaje de pregunta

preguntaClick = document.getElementById('pregunta');

preguntaClick.addEventListener("click", function() {
    swal("Este es el login", "Debes de iniciar sesión con tus credenciales y tu contraseña asignadas", "info");
});