const imagenes = document.querySelectorAll('.img-galeria');
const imagenesLight = document.querySelector('.agregar-imagen');
const contenedorLigt = document.querySelector('.imagen-flotante');

imagenes.forEach(imagen =>{
    imagen.addEventListener('click', ()=>{
        aparecerimagen( imagen.getAttribute('src'))
       
    })
})


contenedorLigt.addEventListener('click', (e)=>{
    if(e.target !== imagenesLight){
        contenedorLigt.classList.toggle('show')
        imagenesLight.classList.toggle('showImage')
    }
})

const aparecerimagen = (imagen) =>{
    imagenesLight.src = imagen;
    contenedorLigt.classList.toggle('show');
    imagenesLight.classList.toggle('showImage')
}