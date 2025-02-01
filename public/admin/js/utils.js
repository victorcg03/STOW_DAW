export function error(mensaje){
  const mensajeBox = document.querySelector('.mensaje');
  mensajeBox.innerText = mensaje;
  mensajeBox.classList.add('visible');  
  setTimeout(()=>{
    mensajeBox.classList.remove('visible');
  }, 1900)
}