// Obtener referencia a los elementos del DOM
const searchForm = document.getElementById('search-product');
const searchInput = document.getElementById('search');
const productosContainer = document.getElementById('productos');

// Manejar el evento de envío del formulario
searchForm.addEventListener('submit', (event) => {
  event.preventDefault(); // Evitar el envío del formulario
  const searchTerm = searchInput.value.trim(); // Obtener el término de búsqueda

  
  // Realizar la solicitud AJAX al servidor PHP
  const xhr = new XMLHttpRequest();
  xhr.open('POST', '../../api/business/dashboard/Buscar.php');
  
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.status === 1) {
        // Limpiar los productos existentes en el contenedor
        productosContainer.innerHTML = '';
        
        // Recorrer los datos de los productos y mostrarlos en el contenedor
        response.dataset.forEach((producto) => {
          const productoHTML = `
            <div class="box">
                <div class="image">
                    <img src="${producto.imagen_producto}" alt="imagen-categoria" />
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
          productosContainer.innerHTML += productoHTML;
        });
      } else {
        productosContainer.innerHTML = 'No se encontraron resultados';
      }
    } else {
      console.error('Error en la solicitud');
    }
  };
  
  const formData = new FormData();
  formData.append('search', searchTerm);
  xhr.send(formData);
});