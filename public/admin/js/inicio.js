import { mensajePop } from './utils.js';
window.addEventListener('load', function () {
  const botonesVer = document.querySelectorAll('.ver button');
  botonesVer.forEach(boton => boton.addEventListener('click', mostrarPedidos));
  const botonesImprimir = document.querySelectorAll('.imprimir button');
  botonesImprimir.forEach(boton => boton.addEventListener('click', imprimirPedidos));
  document.querySelector('.pedidos').addEventListener('click', function (e) {
    if (e.target && e.target.tagName === 'BUTTON' && e.target.hasAttribute('data-idPedido')) {
      cambiarEstado(e);
    }
  });
});
async function mostrarPedidos(e) {
  const pedidos = await obtenerPedidos(e.target.dataset.completados);
  const pedidosContainer = document.querySelector('.pedidos');
  pedidosContainer.innerHTML = '';
  pedidos.forEach(pedido => pedidosContainer.innerHTML += pedidoTemplate(pedido));
}
async function cambiarEstado(e) {
  const ID_PEDIDO = e.target.dataset.idpedido;
  const pedidoCompletado = e.target.classList.contains('completado');
  const res = await fetch("./php/cambiarEstadoPedido.php", {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ ID_PEDIDO, pedidoCompletado })
  });
  const data = await res.json();
  if (data.error) {
    mensajePop(data.msg, true);
    return;
  }
  e.target.classList.toggle('completado');
  e.target.classList.toggle('no-completado');
  mensajePop(data.msg, false);
}
async function imprimirPedidos(e) {
  const pedidos = await obtenerPedidos(e.target.dataset.completados);
  const res = await fetch("./php/generarArchivo.php?pedidos=" + JSON.stringify(pedidos));
  const data = await res.json();
  if (data.error) {
    mensajePop(data.msg, true);
    return;
  }
  mensajePop(data.msg, false);
  console.log(data.ruta);
  console.log(data.nombre);
  const enlace = document.createElement("a");
  enlace.href = data.ruta.replace("../", "./admin/");
  enlace.download = data.nombre;
  document.body.appendChild(enlace);
  enlace.click();
  document.body.removeChild(enlace);
}
async function obtenerPedidos(tipo) {
  const res = await fetch("./php/obtenerPedidos.php?completados=" + tipo);
  const data = await res.json();
  if (data.error) {
    mensajePop(data.msg, true);
    return;
  }
  return data.pedidos;
}
const pedidoTemplate = pedido => `
  <details>
  <summary> <span class="pedido-id">Pedido ${pedido.cabecera.PedidoID}</span> <button data-idPedido='${pedido.cabecera.PedidoID}' class='${pedido.cabecera.Completado == "True" ? "completado" : "no-completado"}'>Alternar estado</button></summary>
  <div>
    <p>Fecha: <span class="fecha-pedido">${pedido.cabecera.FechaPedido}</span></p>
    <p>Cliente: ${pedido.cabecera.CorreoUsuario}</p>
    <p>Productos:</p>
      ${obtenerProductos(pedido.lineas)}
    <p>Subtotal: <span class="subtotal">${pedido.cabecera.SubtotalPedido}€</span></p>
    <p>IVA: <span class="iva">${pedido.cabecera.IvaPedido}€</span></p>
    <p>Envío: <span class="envio">${pedido.cabecera.EnvioPedido}€</span></p>
    <p>Total: <span class="total">${pedido.cabecera.TotalPedido}€</span></p>
  </div>
</details>
`;

const obtenerProductos = (lineas) => {
  return lineas.map(linea => `
    <div class="producto">
      <div class="detalle">
        <p class="producto-id">ID producto: ${linea.ProductoID}</p>
        <p>Talla: <span class="producto-talla">${linea.ProductoTalla}</span></p>
        <p>Cantidad: <span class="producto-cantidad">${linea.Cantidad}</span></p>
        <p>Precio unitario: <span class="producto-precio">${linea.Precio}€</span></p>
        <p>Subtotal producto: <span class="producto-subtotal">${(linea.Precio * linea.Cantidad).toFixed(2)}€</span></p>
      </div>
    </div>
  `).join('');
};
