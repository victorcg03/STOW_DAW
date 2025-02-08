import { mensajePop } from './utils.js';
window.addEventListener('load', cargarPagina);
async function cargarPagina() {
  const productos = await obtenerProductos();
  if (productos) {
    mostrarProductos(productos);
  }
  habilitarFormularioNuevoProducto();
}
function habilitarFormularioNuevoProducto() {
  const inputImagenes = document.querySelector('.nuevoProducto input[type="file"]');
  const carrousel = document.querySelector('.nuevoProducto .imagenes .carrousel');
  inputImagenes.addEventListener('change', function () {
    const imagenes = Array.from(inputImagenes.files);
    carrousel.innerHTML = '';
    let imagenesCargadas = 0;
    imagenes.forEach(imagen => {
      const reader = new FileReader();
      reader.onload = function (e) {
        carrousel.innerHTML += `<div class="img-producto">
                                  <button class="eliminar" data-img="${imagen.name}">Eliminar imagen</button>
                                  <img src="${e.target.result}">
                                </div>`;
        imagenesCargadas++;
        if (imagenesCargadas === imagenes.length) {
          habilitarCarrouseles();
        }
      }
      reader.readAsDataURL(imagen);
    });
  });
  const form = document.querySelector('.nuevoProducto form');
  form.addEventListener('submit', async function (e) {
    e.preventDefault();
      const formData = new FormData(form);
      try {
        const response = await fetch('./php/addProduct', {
          method: 'POST',
          body: formData
        });

        const text = await response.text();
        
        const data = JSON.parse(text);
        if (data.error) {
          mensajePop(data.error, true);
        } else {
          mensajePop(data.success, false);
          form.closest('.nuevoProducto').insertAdjacentHTML('afterend', productoHTML(data.producto));
          habilitarBotonesEliminar();
          habilitarFormularios();
          habilitarBotonesImagenes();
          habilitarCarrouseles();
        }
        form.reset();
        carrousel.innerHTML = '';
      } catch (error) {
        console.error("Error procesando la respuesta:", error);
      }
  });
}
async function obtenerProductos() {
  const response = await fetch('./php/obtenerProductos.php');
  const data = await response.json();
  if (data.error) {
    mensajePop(data.error, true);
    return null;
  }
  return data.productos;
}
function mostrarProductos(productos) {
  const productosContainer = document.querySelector('.productos');
  if (productosContainer) {
    productos.map(producto => {
      productosContainer.innerHTML += productoHTML(producto);
    });
  }
  habilitarCarrouseles();
  habilitarBotonesImagenes();
  habilitarFormularios();
  habilitarBotonesEliminar();
}
function habilitarBotonesImagenes() {
  const botones = document.querySelectorAll('.img-producto button');

  botones.forEach(boton => {
    boton.addEventListener('click', function () {      
      boton.innerText = boton.classList.contains('eliminar') ? "No eliminar imagen" : "Eliminar imagen";
      boton.classList.toggle('eliminar');

      const img = boton.getAttribute('data-img').trim();
      const inputImgs = boton.closest('.producto').querySelector('input[name="imgs"]');

      let imgs = inputImgs.value
        .split(',')
        .map(i => i.trim())

      if (imgs.includes(img)) {
        imgs = imgs.filter(i => i !== img);
      } else {
        imgs.push(img);
      }

      inputImgs.value = imgs.join(',');
    });
  });
}

function habilitarFormularios() {
  const forms = document.querySelectorAll('.producto:not(.nuevoProducto) .info-producto form');
  forms.forEach(form => {
    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      const formData = new FormData(form);
      try {
        const response = await fetch('./php/editarProducto', {
          method: 'POST',
          body: formData
        });

        const text = await response.text();

        const data = JSON.parse(text);
        if (data.error) {
          mensajePop(data.error, true);
        } else {
          mensajePop(data.success, false);
          if (data.nuevasImagenes) {
            const inputImgs = form.closest('.producto').querySelector('input[name="imgs"]');
            let imgs = inputImgs.value
              .split(',')
              .map(i => i.trim())

            imgs.push(...data.nuevasImagenes);
            inputImgs.value = imgs.join(',');
            data.nuevasImagenes.forEach(img => {
              form.closest('.producto').querySelector('.carrousel').innerHTML += imagenTemplate(img);
            });
            habilitarCarrouseles();
            habilitarBotonesImagenes();
          }
        }
        form.querySelector('input[type="file"]').value = '';
      } catch (error) {
        console.error("Error procesando la respuesta:", error);
      }
    });
  });
}

function habilitarBotonesEliminar() {
  const botones = document.querySelectorAll('.info-producto .eliminar');
  botones.forEach(boton => {
    boton.addEventListener('click', async function () {
      let url;
      const id = boton.closest('form').querySelector('input[name="id"]').value;
      if (boton.classList.contains("eliminar")) {
        if (confirm(`¿Estás seguro de que deseas eliminar el producto ${id}?`)) {
          url = './php/eliminarProducto.php';
          boton.innerText = "Recuperar producto";
        }
      } else {
        url = "./php/recuperarProducto.php";
        boton.innerText = "Recuperar producto";
      }
      const formData = new FormData();
      formData.append('id', id);
      const response = await fetch(url, {
        method: 'POST',
        body: formData
      });
      const data = await response.json();
      if (data.error) {
        mensajePop(data.error, true);
      } else {
        mensajePop(data.success, false);
      }
      boton.classList.toggle('eliminar');
    });
  });
}
const productoHTML = producto => {
  const imagenes = producto.Imagenes.split(',');
  const stocks = Object.fromEntries(
    producto.Stock.split(',').map(item => {
      const [talla, stock] = item.trim().split(':');
      return [talla, Number(stock)];
    })
  );

  return `<div class="producto">
            <div class="imagenes">
              <span class="fa-solid fa-angle-left"></span>
              <span class="fa-solid fa-angle-right"></span>
              <div class="carrousel">
              ${imagenes.map(imagen => imagenTemplate(imagen)).join('')}
              </div>
            </div>
            <div class="info-producto">
                <form>
                  <input type="text" name="id" value="${producto.ID}" hidden>
                  <input type="text" name="imgs" id="imgs-${producto.ID}" value="${producto.Imagenes}" hidden>
                  <div class="form-row">
                    <label for="imgnes-${producto.ID}">Imagenes:</label>  
                    <input type="file" id="imgnes-${producto.ID}" name="imagenes[]" accept="image/*" multiple>
                  </div>
                  <div class="form-row">
                    <label for="nombre-${producto.ID}">Nombre:</label>  
                    <input type="text" name="nombre" id="nombre-${producto.ID}" value="${producto.Nombre}" required>
                  </div>
                  <div class="form-row">
                    <label for="precio-${producto.ID}">Precio:</label>  
                    <input step="0.01" min="0.1" type="number" name="precio" id="precio-${producto.ID}" value="${producto.Precio}" required>
                  </div>
                  <div class="form-row">
                    <label for="sexo-${producto.ID}">Sexo:</label>  
                    <input type="text" name="sexo" id="sexo-${producto.ID}" value="${producto.Sexo}" required>
                  </div>
                  <div class="form-row">
                    <label for="color-${producto.ID}">Color:</label>  
                    <input type="text" name="color" id="color-${producto.ID}" value="${producto.Color}" required>
                  </div>
                  <div class="form-row">
                    <label for="clase-${producto.ID}">Clase:</label>  
                    <input type="text" name="clase" id="clase-${producto.ID}" value="${producto.ClaseProducto}" required>
                  </div>
                  <div class="stocks">
                  ${Object.entries(stocks).map(([talla, valor]) =>
    `<div>
                      <label for="${talla}-${producto.ID}">${talla}</label>
                      <input type="number" min="0" name="${talla}" value="${valor}" id="${talla}-${producto.ID}" required>
                    </div>`).join('')}
                  </div>
                  <button type="submit">Guardar cambios</button>
                  <button class="${producto.activo == 1 ? 'eliminar' : ''}" type="button">${producto.activo == 1 ? 'Eliminar' : 'Recuperar'} producto</button>
                </form>
            </div>
          </div>
`
}
const imagenTemplate = (imagen) =>
  `
    <div class="img-producto">
      <button class="eliminar" data-img="${imagen.trim()}">Eliminar imagen</button>
      <img src="../img/${imagen.trim()}">
    </div>`
  ;
export function habilitarCarrouseles() {
  const carrouseles = document.querySelectorAll('.carrousel');
  carrouseles.forEach(carrousel => {
    const numImagenes = carrousel.children.length;
    let imagenActual = 0;
    const flechas = carrousel.parentElement.querySelectorAll('.fa-angle-left, .fa-angle-right');
    flechas.forEach(flecha => {
      flecha.addEventListener('click', () => {
        if (flecha.classList.contains('fa-angle-left')) {
          moverIzquierda();
        } else {
          moverDerecha();
        }
        function actualizarCarrusel() {
          carrousel.style.transform = `translateX(-${imagenActual * 100}%)`;
        }

        function moverDerecha() {
          if (imagenActual < numImagenes - 1) {
            imagenActual++;
          } else {
            imagenActual = 0;
          }
          actualizarCarrusel();
        }

        function moverIzquierda() {
          if (imagenActual > 0) {
            imagenActual--;
          } else {
            imagenActual = numImagenes - 1;
          }
          actualizarCarrusel();
        }
        const images = carrousel.querySelectorAll('img');
        images.forEach(img => {
          img.addEventListener("touchstart", (e) => {
            startX = e.touches[0].clientX;
          });

          img.addEventListener("touchend", (e) => {
            endX = e.changedTouches[0].clientX;
            if (startX - endX > 50) {
              moverDerecha();
            } else if (endX - startX > 50) {
              moverIzquierda();
            }
          });
        });

      });
    });

  });
}