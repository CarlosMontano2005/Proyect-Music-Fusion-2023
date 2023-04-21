/*
*   Controlador de uso general en las páginas web del sitio privado.
*   Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'business/dashboard/usuario.php';

// const USER_API = 'business/dashboard/usuario.php';
// Constantes para establecer las etiquetas de encabezado y pie de la página web.
const HEADER = document.querySelector('header');
const NAV = document.querySelector('nav');
const FOOTER = document.querySelector('footer');

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    // Petición para obtener en nombre del usuario que ha iniciado sesión.
    const JSON = await dataFetch(USER_API, 'getUser');
    // Se verifica si el usuario está autenticado, de lo contrario se envía a iniciar sesión.
    if (JSON.session) {
        // Se comprueba si existe un alias definido para el usuario, de lo contrario se muestra un mensaje con la excepción.
       if (JSON.status) {
          //Método manejador de eventos para cuando el documento ha cargado.
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
        });
        HEADER.innerHTML = `
        <div class="home-content">
        <i class='bx bx-menu' id="btn"></i>
        <!--comentario 15/02/2023-->
        <span class="text">Dashboard</span>
        <label class="info" id="greeting">saludo</label>
    </div>
    <div class="bottom-content">
        <div class="nav-barra-time">
            <label>lunes feb. 30 2022</label>
        </div>
        <li class="mode">
            <div class="sun-moon">
                <i class='bx bx-moon icon moon'></i>
                <i class='bx bx-sun icon sun'></i>
            </div>
            <span class="mode-text text">Modo Dark</span>

            <div class="toggle-switch">
                <span class="switch"></span>
            </div>
        </li>
    </div>`
    

    ;
            NAV.innerHTML = `<div class="logo-details">
            <img src="../../img/logos/logo_blanco_horizontal.png" alt="logo">
        </div>
        <ul class="nav-links">
            <li>
                <a href="../../views/dashboard/dashboard.html">
                    <i class='bx bxs-home-alt-2'></i>
                    <span class="link_name">Inicio</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="../../views/dashboard/dashboard.html">Inicio</a></li>
                </ul>
            </li>
            <li>
                <a href="../../views/dashboard/usuarios.html">
                    <i class='bx bx-user'></i>
                    <span class="link_name">Usuario</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="../../views/dashboard/usuarios.html">Usuario</a></li>
                </ul>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class='bx bxs-package'></i>
                        <span class="link_name">Pedidos</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Pedidos</a></li>
                    <li><a href="../../views/dashboard/pedidos.html">Pedidos</a></li>
                    <li><a href="../../views/dashboard/detalles_pedidos.html">Detalles Pedidost</a></li>
                </ul>
            </li>

            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class='bx bx-book-alt'></i>
                        <span class="link_name">Productos</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Productos</a></li>
                    <li><a href="../../views/dashboard/productos.html">Productos</a></li>
                    <li><a href="../../views/dashboard/marcas.html">Marcas</a></li>
                </ul>
            </li>
            <li>
                <a href="../../views/dashboard/clientes.html">
                    <i class='bx bxs-user-account'></i>
                    <span class="link_name">Clientes</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="../../views/dashboard/clientes.html">Clientes</a></li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-cog'></i>
                    <span class="link_name">Sobre el sistema</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="#">Sobre el sistema</a></li>
                </ul>
            </li>
            <li>

                <!-- -->
                <div class="profile-details">
                    <div class="profile-content">
                        
                        <!--<img src="../../img/people/charlie.jpg" alt="profileImg"> -->
                        <img src="${SERVER_URL}img/people/${JSON.foto}" alt="profileImg"> 
                    </div>
                    <div class="name-job">
                        <div class="profile_name">${JSON.username}</div>
                        <div class="job">Vendedor</div>
                    </div>
                    <a onclick="logOut()"><i class='bx bx-log-out'></i></a>
                </div>
            </li>
        </ul>
        
        `;
           
            
  let arrow = document.querySelectorAll(".arrow");
  for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e) => {
      let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
      arrowParent.classList.toggle("showMenu");
    });
  }
    var sidebarBtn = document.querySelector(".bx-menu");
    var sidebar = document.querySelector(".sidebar");
    const body = document.querySelector('body');
  
    toggle = body.querySelector(".toggle");
    searchBtn = body.querySelector(".search-box"),
    modeSwitch = body.querySelector(".toggle-switch");
    modeText = body.querySelector(".mode-text");
    console.log(sidebarBtn);
  
  sidebarBtn.addEventListener("click", () => {
    sidebar.classList.toggle("close");
  });
  
  
  modeSwitch.addEventListener("click", () => {
    body.classList.toggle("dark");
  
    if (body.classList.contains("dark")) {
      modeText.innerText = "Light mode";
    } else {
      modeText.innerText = "Dark mode";
    }
  });


  
           // Se inicializa el componente Dropdown para que funcione la lista desplegable en los menús.
            //M.Dropdown.init(document.querySelectorAll('.dropdown-trigger'));
            // Se inicializa el componente Sidenav para que funcione la navegación lateral.
           // M.Sidenav.init(document.querySelectorAll('.sidenav'));
        } else {
            sweetAlert(3, JSON.exception, false, 'index.html');
        }
    } else {
        // Se comprueba si la página web es la principal, de lo contrario se direcciona a iniciar sesión.
       if (location.pathname == '/proyect-music-fusion-2023/views/dashboard/index.html') {
            /* HEADER.innerHTML = `
                <div class="navbar-fixed">
                    <nav>
                        <div class="nav-wrapper center-align">
                            <a href="index.html" class="brand-logo"><i class="material-icons">dashboard</i></a>
                        </div>
                    </nav>
                </div>
            `;
            FOOTER.innerHTML = `
                <div class="container">
                    <div class="row">
                        <b>Dashboard de CoffeeShop</b>
                    </div>
                </div>
                <div class="footer-copyright">
                    <div class="container">
                        <span>© 2018-2023 Copyright CoffeeShop. Todos los derechos reservados.</span>
                        <span class="right">Diseñado con
                            <a href="http://materializecss.com/" target="_blank">
                                <img src="../../resources/img/materialize.png" height="20" style="vertical-align:middle" alt="Materialize">
                            </a>
                        </span>
                    </div>
                </div>
            `;*/
            // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
           // M.Tooltip.init(document.querySelectorAll('.tooltipped'));
        } else {
            location.href = 'index.html';
        }
    }
});