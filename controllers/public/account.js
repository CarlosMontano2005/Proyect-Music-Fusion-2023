/*
*   Controlador es de uso general en las páginas web del sitio público.
*   Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'business/public/clientes.php';
// Constantes para establecer las etiquetas de encabezado y pie de la página web.
const HEADER = document.querySelector('header');
const FOOTER = document.querySelector('footer');

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    // Petición para obtener en nombre del usuario que ha iniciado sesión.
    const JSON = await dataFetch(USER_API, 'getUser');
    // Se comprueba si el usuario está autenticado para establecer el encabezado respectivo.
    if (JSON.session) {
        HEADER.innerHTML = `
        <a href="#" class="logo"><i class="fas-fa-utensils"></i>Music Funsion</a>

        <nav class="navbar">
            <a class="active" href="#inicio">Inicio</a>
            <a href="#intrumentos">Instrumentos</a>
            <a href="#accesorios">Accesorios</a>
            <a href="#acerca de">Acerca de</a>
            <a href="#tendencia">tendencia</a>
            <a href="#orden">orden</a>
        </nav>

        <div class="icons">
            <i class='bx bx-menu' id="menu-bars"></i>
            <i class='bx bx-search-alt-2' id="search-icon"></i>
            <i class='bx bx-shopping-bag'></i>
            <a onclick="logOut()"><i class='bx bx-log-out'></i></a>    
        </div>

        
        `
        ;
        
    } else {
        HEADER.innerHTML = `
        <a href="#" class="logo"><i class="fas-fa-utensils"></i>Music Funsion</a>

        <nav class="navbar">
            <a class="active" href="#inicio">Inicio</a>
            <a href="#intrumentos">Instrumentos</a>
            <a href="#accesorios">Accesorios</a>
            <a href="#acerca de">Acerca de</a>
            <a href="#tendencia">tendencia</a>
            <a href="#orden">Ordenes</a>
        </nav>

        <div class="icons">
            <i class='bx bx-menu' id="menu-bars"></i>
            <i class='bx bx-search-alt-2' id="search-icon"></i>
            <i class='bx bx-shopping-bag'></i>
            <a href="../../views/public/login.html"><i class='bx bx-user' ></i></a>    
        </div>
        <form action="" id="search-form">
      <input
        type="search"
        placeholder="Search here..."
        name=""
        id="search-box"
      />
      <label for="search-box" class="bx bx-search-alt-2"></label>
      <i class="bx bx-x abrir-search" id="close"></i>
    </form>
        `
        ;
        let menu = document.querySelector('#menu-bars');
        let navbar = document.querySelector('.navbar');

        menu.onclick = () =>{
            menu.classList.toggle("menu-bars");
            navbar.classList.toggle('active');
        }

        window.onscroll = () =>{
            menu.classList.remove("abrir-search");
            navbar.classList.remove('active');
        }

        document.querySelector('#search-icon').onclick = () =>{
            document.querySelector('#search-form').classList.toggle('active');
        }

        document.querySelector('#close').onclick = () =>{
            document.querySelector('#search-form').classList.remove('active');
        }

        var swiper = new Swiper(".home-slider", {
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
              delay: 3500,
              disableOnInteraction: false,
            },
            pagination: {
              el: ".swiper-pagination",
              clickable: true,
            },
            loop:true,
          });

          var swiper = new Swiper(".review-slider", {
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
              delay: 7500,
              disableOnInteraction: false,
            },
            loop:true,
            breakpoints: {
              0: {
                slidesPerView: 1,
              },
              640: {
                slidesPerView: 2,
              },
              768: {
                slidesPerView: 2,
              },
              1024: {
                slidesPerView: 3,
              },
            },
          });
        
        
    }
    
    // Se establece el pie del encabezado.
    FOOTER.innerHTML = `
    <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <h6>MUSIC FUSION</h6>
        <p class="text-justify">
            Music Fusion © 2022 All Rights Reserved - Creado Estudiantes del Instituto Tecnico Ricaldone 
        </p>
      </div>

      <div class="col-xs-6 col-md-3">
        <h6>Socios</h6>
        <ul class="footer-links">
          <li><a href="http://scanfcode.com/category/c-language/">OMNI MUSIC</a></li>
          <li>
            <a href="http://scanfcode.com/category/front-end-development/"
              >SUPERSONIDOS MUSIC</a
            >
          </li>
          <li>
            <a href="http://scanfcode.com/category/back-end-development/"
              >PROAVANCE</a
            >
          </li>
        </ul>
      </div>

      <div class="col-xs-6 col-md-3">
        <h6>Patrocinadores</h6>
        <ul class="footer-links">
          
          <li>
            <a href="http://scanfcode.com/contribute-at-scanfcode/"
              >COCACOLA</a
            >
          </li>
          <li>
            <a href="http://scanfcode.com/privacy-policy/"
              >MUSIC LIC</a
            >
          </li>
          <li><a href="http://scanfcode.com/sitemap/">SPOTIFY</a></li>
        </ul>
      </div>
    </div>
    <hr />
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-sm-6 col-xs-12">
        <p class="copyright-text">
          Music Fusion &copy; 2022 All Rights Reserved - Creado Estudiantes del Instituto Tecnico Ricaldone 
          <a href="#">Music Fusion</a>.
        </p>
      </div>

      <div class="col-md-4 col-sm-6 col-xs-12">
        <ul class="social-icons">
          <li>
            <a class="facebook" href="#"><i class='bx bxl-facebook'></i></a>
          </li>
          <li>
            <a class="twitter" href="#"><i class='bx bxl-twitter' ></i></a>
          </li>
          <li>
            <a class="spotify" href="#"><i class='bx bxl-spotify' ></i></a>
          </li>
          <li>
            <a class="linkedin" href="#"><i class='bx bx-music' ></i></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
    `;
    
    // Se inicializa el componente Sidenav para que funcione la navegación lateral.
    
});