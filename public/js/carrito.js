let productosCarritoCookie;
window.addEventListener('load', documentoListo);
async function documentoListo() {
    await comprobarSesion();
    const cookie = getCookie("productosCarrito");
    productosCarritoCookie = cookie ? JSON.parse(cookie) : [];
    cargarProductos();
    calcularResumen();
    document.querySelectorAll(".producto input").forEach(input => input.addEventListener("change", actualizarDatos));
    document.querySelectorAll(".producto i").forEach(boton => boton.addEventListener("click", eliminarProducto));
}
async function comprobarSesion() {
    let respuesta = await fetch("./verificarSesionIniciada.php");
    let data = await respuesta.json();
    if (!data.loggedIn) {
        window.location.href = "login";
    }
}
function cargarProductos() {
    const carrito = document.querySelector(".izquierda");
    if (productosCarritoCookie.length > 0) {
        document.getElementById("mensaje").classList.add("invisible");
        productosCarritoCookie.forEach(producto => {
            carrito.innerHTML += `
            <div class="producto" data-id="${producto.idProducto}">
                <img src="./img/${producto.img}">
                <div class="info">
                    <p class="nombre">${producto.nombre}</p>
                    <p class="talla">Talla: <span>${producto.talla}</span></p>
                    <p class="unidades">Unidades: <input type="number" value="${producto.cantidad}" max="${producto.stock}" min="1"></p>
                    <p class="precio">Precio por unidad: <span>${parseFloat(producto.precio).toFixed(2)}</span>€</p>
                    <p class="total-producto">Total: <span>${(producto.cantidad * parseFloat(producto.precio)).toFixed(2)}</span>€</p>
                </div>
                <i class="fa-regular fa-trash-can"></i>
            </div>
            `
        });
        document.querySelector(".realizar-pedido").addEventListener("click", realizarPedido);
    }
}
function calcularResumen(){
    const costes = productosCarritoCookie.reduce((costes, producto) =>{
        const totalProducto = producto.cantidad * parseFloat(producto.precio);
        costes.subtotal += totalProducto;
        costes.iva += totalProducto * 0.21;
        costes.envio += producto.cantidad * 1.27;
        return costes;
    }, {subtotal: 0, envio: 0, iva: 0});
    document.getElementById("subtotal").innerText = costes.subtotal.toFixed(2);
    document.getElementById("envio").innerText = costes.envio.toFixed(2);
    document.getElementById("iva").innerText = costes.iva.toFixed(2);
    document.getElementById("total").innerText = (costes.iva + costes.envio + costes.subtotal).toFixed(2);
}
function actualizarDatos(e){    
    const producto = e.target.closest(".producto");
    const precioUnidad = parseFloat(producto.querySelector(".precio span").innerText);
    const talla = producto.querySelector(".talla span").innerText;
    const id = producto.dataset.id;
    productosCarritoCookie.forEach(producto => {
        if (producto.idProducto.trim() == id.trim() && producto.talla.trim() == talla.trim()) {
            producto.cantidad = e.target.value;
        }
    });
    producto.querySelector(".total-producto span").innerText = (e.target.value * precioUnidad).toFixed(2);
    document.cookie = `productosCarrito=${encodeURIComponent(JSON.stringify(productosCarritoCookie))}; path=/; max-age=3600`;
    calcularResumen();
}
function eliminarProducto(e){
    const producto = e.target.closest(".producto");
    const talla = producto.querySelector(".talla span").innerText.trim();
    const id = producto.dataset.id;
    productosCarritoCookie = productosCarritoCookie.filter(producto =>
        !(producto.idProducto == id && producto.talla.trim() == talla)
    );

    producto.classList.add("animacionEliminar");
    setTimeout(() => {
        producto.remove();
        if (productosCarritoCookie.length == 0) {
            document.querySelector("#mensaje").classList.remove("invisible");
            document.querySelector(".realizar-pedido").removeEventListener("click", realizarPedido);
        }
    }, 500);
    document.cookie = `productosCarrito=${encodeURIComponent(JSON.stringify(productosCarritoCookie))}; path=/; max-age=3600`;
    calcularResumen();
}
async function realizarPedido(){
    const {user} = await (await fetch("./verificarSesionIniciada.php")).json();
    const costes = productosCarritoCookie.reduce((costes, producto) =>{
        const totalProducto = producto.cantidad * parseFloat(producto.precio);
        costes.subtotal += totalProducto;
        costes.iva += totalProducto * 0.21;
        costes.envio += producto.cantidad * 1.27;
        return costes;
    }, {subtotal: 0, envio: 0, iva: 0});
    document.cookie = `productosCarrito=; path=/; max-age=3600`;
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "procesarPedido";
    const datosPedido = {
        productos: productosCarritoCookie,
        costes: costes,
        user : user
    };
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "datosPedido";
    input.value = JSON.stringify(datosPedido);
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
const getCookie = (name) => {
    const cookies = document.cookie.split("; ");
    for (let cookie of cookies) {
        const [key, value] = cookie.split("=");
        if (key === name) return decodeURIComponent(value);
    }
    return null;
};