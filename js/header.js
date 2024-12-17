window.addEventListener("load", () => {
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
    document.querySelectorAll("header .fa-solid").forEach(el => el.addEventListener("click", () => {
        let visible = document.querySelector(`.visible:not(.${el.dataset.menu})`);
        if (visible) {
            visible.classList.remove("visible");
        }
        setTimeout(() => {
            document.querySelector(`.${el.dataset.menu}`).classList.toggle("visible");
        }, visible ? 400 : 0);
    }));
    document.querySelector("main").addEventListener("click", (event) => {
        let menu = document.querySelector(".visible");
        if (menu && (!menu.classList.contains("carrito") || !event.target.classList.contains("anadirCesta"))) {
            menu.classList.remove("visible");
        }
    });
    const cookie = getCookie("productosCarrito");      
    let productosCarritoCookie = cookie ? JSON.parse(cookie) : [];
    productosCarritoCookie.forEach(p => {
        document.querySelector(".carrito").innerHTML +=  productoCestaPlantillaHeader(p.talla, p.idProducto, p.img, p.nombre, p.stock, p.precio, p.cantidad);
    });
    search.addEventListener("input", async () => {
        if (search.value.length > 0) {
            try {
                let response = await fetch(`./search.php?search=${search.value}`);
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
            }, 700);
        }
    });
});


const getCookie = (name) => {
    const cookies = document.cookie.split("; ");
    for (let cookie of cookies) {
        const [key, value] = cookie.split("=");
        if (key === name) return decodeURIComponent(value);
    }
    return null;
};

const productoCestaPlantillaHeader = (talla, idProducto, img, nombre, max, precio, cantidad) => {
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