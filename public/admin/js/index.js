comprobarSession();
import { mensajePop } from './utils.js';
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
      mensajePop(data.error, true);
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