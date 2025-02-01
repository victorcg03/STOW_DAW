import { error } from './utils.js';
window.addEventListener('load', function() {
  const botonesVer = document.querySelectorAll('.ver button');
  botonesVer.forEach(boton => boton.addEventListener('click', mostrarPedidos));
});
async function mostrarPedidos(e){
  const verCompletados = e.target.dataset.completados;
  const res = await fetch("./php/obtenerPedidos.php?completados=" + verCompletados);
  const data = await res.json();
  if (data.error) {
    error(data.msg);
    return;
  }
  const pedidos = data.pedidos;
  const pedidosContainer = document.querySelector('.pedidos');
  pedidos.map(pedido => {
    pedidos.innerHTML = pedidoTemplate(pedido);
  });
}
const pedidoTemplate = pedido => `
  
`;

