comprobarSession();
window.addEventListener('load', function() {
  const formulario = document.querySelector('form');
  formulario.addEventListener('submit', async function(event) {
    event.preventDefault();
    const formData = new FormData(formulario);
    const res = await fetch('./php/login.php', {
      method: 'POST',
      body: formData
    });
    const data = await res.json();
    if (data.error) {
      error(data.error);
    }
    if (data.sesionIniciada) {
      window.location.href = 'inicio';
    }
  });
});

async function comprobarSession(){
  const res = await fetch("./php/comprobarSesionIniciada.php");
  const data = await res.json();
  if (data.sesionIniciada) {
    window.location.href = 'inicio';
  }
}
function error(mensaje){
  const mensajeBox = document.querySelector('.mensaje');
  mensajeBox.innerText = mensaje;
  mensajeBox.classList.add('visible');
  setTimeout(()=>{
    mensajeBox.classList.remove('visible');
  }, 1900)
}