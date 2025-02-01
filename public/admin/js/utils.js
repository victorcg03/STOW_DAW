export function mensajePop(mensaje, error){
  const mensajeBox = document.querySelector('.mensaje');
  mensajeBox.innerText = mensaje;
  error ? mensajeBox.classList.add('error') : mensajeBox.classList.add('notificacion');
  mensajeBox.classList.add('visible');  
  setTimeout(()=>{
    mensajeBox.classList.remove('visible');
  }, 1900)
}