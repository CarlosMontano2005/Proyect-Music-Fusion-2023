// Obtener referencia a los elementos del DOM
const searchForm = document.getElementById('search-product');
const searchInput = document.getElementById('search');
const productosContainer = document.getElementById('productos');

// Agregar un evento de escucha al formulario de búsqueda
searchForm.addEventListener('submit', (e) => {
  e.preventDefault(); // Evitar el envío del formulario
  const searchTerm = searchInput.value.trim(); // Obtener el término de búsqueda
  
  // Realizar la solicitud AJAX al servidor PHP para buscar productos
  const xhr = new XMLHttpRequest();
  xhr.open('POST', '../../api/business/public/Buscar.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.status === 1) {
        // Mostrar los productos encontrados en el contenedor
        displayProductos(response.dataset);
      } else {
        // Mostrar un mensaje de error o vacío en el contenedor
        productosContainer.innerHTML = 'No se encontraron productos.';
      }
    }
  };
  xhr.send('action=search&search=' + encodeURIComponent(searchTerm));
});

// Función para mostrar los productos en el contenedor
function displayProductos(productos) {
  let html = '';
  productos.forEach((producto) => {
    html += `
      <div class="box">
        <div class="image">
          <img src="${SERVER_URL}img/productos/${producto.imagen_producto}" alt="imagen-categoria" />
          <a href="detail.html?id=${producto.id_producto}" class="bx bx-shopping-bag bx-left"></a>
        </div>
        <div class="content">
          <div class="stars">
            <i class="bx bxs-star"></i>
            <i class="bx bxs-star"></i>
            <i class="bx bxs-star"></i>
            <i class="bx bxs-star"></i>
            <i class="bx bxs-star-half"></i>
          </div>
          <h3>${producto.nombre_producto}</h3>
          <p>${producto.nombre_categoria}</p>
          <a href="detail.html?id=${producto.id_producto}" class="btn">Ver Detalle</a>
          <span class="price">Precio(US$) ${producto.precio_producto}</span>
        </div>
      </div>
    `;
  });

  // Agregar el HTML al contenedor de productos
  productosContainer.innerHTML = html;
}
