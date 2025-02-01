window.addEventListener("load", () => {
    traductorGoogle();
    habilitarBotonesHeader();
    habilitarMain();
    cargarCarrito();
    habilitarSearch();
    habilitarInteraccionesProductos();
});

function traductorGoogle(){
    setTimeout(() => {
        const translateElement = document.querySelector("#google_translate_element .skiptranslate");
        if (translateElement) {
            translateElement.childNodes.forEach(nodo => {
                if (nodo.nodeName === "#text" || nodo.nodeName === "SPAN") {
                    nodo.remove();
                }
            });
        }
    }, 500);
}
function habilitarBotonesHeader(){
    document.querySelectorAll("header .fa-solid").forEach(el => el.addEventListener("click", () => {
        let visible = document.querySelector(`.visible:not(.${el.dataset.menu})`);
        if (visible) {
            visible.classList.remove("visible");
        }
        setTimeout(() => {
            document.querySelector(`.${el.dataset.menu}`).classList.toggle("visible");
        }, visible ? 400 : 0);
    }));
}
function habilitarMain(){
    document.querySelector("main").addEventListener("click", (event) => {
        let menu = document.querySelector(".visible");
        if (menu && (!menu.classList.contains("carrito") || !event.target.classList.contains("anadirCesta"))) {
            menu.classList.remove("visible");
        }
    });
}
function cargarCarrito(){
    const cookie = getCookie("productosCarrito");
    let productosCarritoCookie = cookie ? JSON.parse(cookie) : [];
    const carrito = document.querySelector("#carrito");
    if (productosCarritoCookie.length > 0) {
        document.querySelector(".carrito .mensaje").classList.add("display-none");
        document.querySelector(".carrito .info-carrito").classList.remove("display-none");
        productosCarritoCookie.forEach(p => {
            carrito.innerHTML += productoCestaPlantilla(p.talla, p.idProducto, p.img, p.nombre, p.stock, p.precio, p.cantidad);
        });
        actualizarInfoCesta(productosCarritoCookie);
    }
}
function habilitarSearch(){
    search.addEventListener("input", async () => {
        if (search.value.length > 0) {
            try {
                let response = await fetch(`./search.php?search=${search.value}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
                );
                if (!response.ok) {
                    console.error("Error en la respuesta del servidor", response.status);
                    return;
                }
                let resultados = document.querySelector(".resultados");
                resultados.innerHTML = "";
                let productos = await response.json();;
                productos.forEach(producto => {
                    resultados.innerHTML += `<div class="producto">
                        <img src="./img/img1.jpg">
                        <div class="info-producto">
                            <p class="nombre">${producto["Nombre"] + "|" + producto["Sexo"]}</p>
                            <p class="precio">${producto["Precio"]}€</p>
                        </div>
                    </div>`
                });
            } catch (error) {
                console.error("Error al procesar la respuesta:", error);
            }
        }
    });
}
function habilitarInteraccionesProductos(){
    document.querySelector(".carrito").addEventListener("click", (event) => {
        if (event.target.tagName === "I") {
            event.target.closest(".producto").classList.add("eliminar");
            const cookie = getCookie("productosCarrito");
            let productosCarritoCookie = cookie ? JSON.parse(cookie) : [];

            productosCarritoCookie = productosCarritoCookie.filter(producto =>
                !(producto.idProducto == event.target.dataset.id && producto.talla == event.target.dataset.talla)
            );
            document.cookie = `productosCarrito=${encodeURIComponent(JSON.stringify(productosCarritoCookie))}; path=/; max-age=3600`;

            setTimeout(() => {
                event.target.closest(".producto").remove()
                if (productosCarritoCookie.length == 0) {
                    document.querySelector(".carrito .mensaje").classList.remove("display-none");
                    document.querySelector(".carrito .info-carrito").classList.add("display-none");
                }
            }, 700);
            actualizarInfoCesta(productosCarritoCookie);
        }
        if (event.target.type === "number") {
            event.preventDefault();
            const cookie = getCookie("productosCarrito");
            const productoClick = event.target.closest(".producto");
            const id = productoClick.dataset.id;
            const talla = productoClick.dataset.talla;
            const input = document.querySelector(`.carrito .producto[data-talla="${talla}"][data-id="${id}"] input`);
            const stock = parseInt(input.max);
            const nuevaCantidad = parseInt(input.value);

            let productosCarritoCookie = cookie ? JSON.parse(cookie) : [];
            productosCarritoCookie.forEach(producto => {
                if (producto.idProducto == id && producto.talla == talla) {
                    if (nuevaCantidad > 0 && nuevaCantidad <= stock) {
                        producto.cantidad = nuevaCantidad;
                        input.value = producto.cantidad;
                    } else {
                        input.value = producto.cantidad;
                    }
                    return;
                }
            });

            document.cookie = `productosCarrito=${encodeURIComponent(JSON.stringify(productosCarritoCookie))}; path=/; max-age=3600`;
            actualizarInfoCesta(productosCarritoCookie);
        }
    });
}

//Plantillas y funcionalidades
const getCookie = (name) => {
    const cookies = document.cookie.split("; ");
    for (let cookie of cookies) {
        const [key, value] = cookie.split("=");
        if (key === name) return decodeURIComponent(value);
    }
    return null;
};

const productoCestaPlantilla = (talla, idProducto, img, nombre, max, precio, cantidad) => {
    return `<div class="producto" data-talla="${talla}" data-id="${idProducto}">
                <img src="./img/${img}">
                <div class="producto-info">
                    <p class="nombre">${nombre}</p>
                    <p data-talla="${talla}" class="talla">Talla: ${talla}</p>
                    <div class="cantidad">Cantidad: <input type="number" min="1" max="${max}" value="${cantidad}"></div>
                    <p>Precio: ${precio}</p>
                </div>
                <i class="fa-regular fa-trash-can" data-id="${idProducto}" data-talla="${talla}"></i>
            </div>`;
}
async function comprobarSesion() {
    let respuesta = await fetch("./verificarSesionIniciada.php");
    let data = await respuesta.json();
    if (!data.loggedIn) {
        window.location.href = "login.php";
        return false;
    }
    return data.user;
}
function actualizarInfoCesta(productos) {
    const total = productos.reduce((totalCesta, producto) => { 
        return totalCesta + (parseFloat(producto.precio) * producto.cantidad);
    }, 0).toFixed(2);
    document.getElementById("total").innerText = total;
    const unidades = productos.reduce((numProductos, producto) =>{
        return numProductos + producto.cantidad;
    },0);
    document.getElementById("unidades").innerText = unidades;
}