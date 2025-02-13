window.addEventListener('load', function() {
  const fotos = document.querySelectorAll('img');
  const dialog = document.querySelector('dialog');
  fotos.forEach(foto => {
    foto.addEventListener('click', function() {
      dialog.showModal();
      dialog.querySelector('img').src = foto.src;
    });
  });
});