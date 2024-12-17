window.addEventListener('load', () => {
    document.querySelectorAll(".fa-angle-right").forEach(rightArrow => {
        const leftArrow = rightArrow.closest(".imagenes").querySelector(".fa-angle-left");
        const carrousel = rightArrow.closest(".imagenes").querySelector(".carrousel");
        const images = carrousel.querySelectorAll("img");
        let currentIndex = 0;

        rightArrow.addEventListener("click", () => {
            if (currentIndex < images.length - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            carrousel.style.transform = `translateX(-${currentIndex * 100}%)`;
        });
        leftArrow.addEventListener("click", () => {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = images.length - 1;
            }
            carrousel.style.transform = `translateX(-${currentIndex * 100}%)`;
        });
    });
    document.querySelectorAll(".producto .fa-regular.fa-heart").forEach(corazon => corazon.addEventListener("click", async () => {


        const corazonFondo = corazon.closest(".producto").querySelector(".fa-solid");
        const idProducto = corazon.closest(".producto").dataset.id;
        let usuario = await comprobarSesion();
        fetch("saveLike.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                "idProducto": idProducto,
                "usuario": usuario
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    corazonFondo.style.display = window.getComputedStyle(corazonFondo).display == "none" ? "block" : "none";
                } else {
                    console.log(data.message);
                }
            })
            .catch(error => {
                console.error("Error guardando el like:", error);
            });
    }));

    document.querySelectorAll(".producto .anadirCesta").forEach(botonAnadir => botonAnadir.addEventListener("click", async (event) => {
        await comprobarSesion();
        const cookie = getCookie("productosCarrito");
        let productosCarritoCookie = cookie ? JSON.parse(cookie) : [];
        const producto = botonAnadir.closest(".producto");
        const talla = producto.querySelector(".tallaProducto").value;
        const id = producto.dataset.id;
        let productoEncontrado = false;

        productosCarritoCookie.forEach(producto => {            
            if (producto.idProducto == id && producto.talla == talla) {
                producto.cantidad++;
                document.querySelector(`.carrito .producto[data-talla="${talla}"][data-id="${id}"] input`).setAttribute('value', producto.cantidad);
                productoEncontrado = true;
                return;
            }
        });

        if (!productoEncontrado) {
            const imgSRC = producto.querySelectorAll("img")[0].src.split("/");
            const img = imgSRC[imgSRC.length - 1];
            const nombre = producto.querySelector(".nombre").innerText;
            const stock = producto.querySelector(`.tallaProducto > option[value="${talla}"]`).dataset.stock;
            const precio = producto.querySelector(".precio").innerText;
            const productoCookie = {
                idProducto: id,
                nombre: nombre,
                talla: talla,
                img: img,
                stock: stock,
                precio: precio,
                cantidad: 1
            };
            productosCarritoCookie.push(productoCookie);

            document.querySelector(".carrito").innerHTML += productoCestaPlantilla(talla, id, img, nombre, stock, precio);
        }
        document.cookie = `productosCarrito=${encodeURIComponent(JSON.stringify(productosCarritoCookie))}; path=/; max-age=3600`;
    }));

    async function comprobarSesion() {
        let respuesta = await fetch("./verificarSesionIniciada.php");
        let data = await respuesta.json();
        if (!data.loggedIn) {
            window.location.href = "login.php";
        }
        return data.user;

    }
});
const productoCestaPlantilla = (talla, idProducto, img, nombre, max, precio) => {
    return `<div class="producto" data-talla="${talla}" data-id="${idProducto}">
                <img src="./img/${img}">
                <div class="producto-info">
                    <p class="nombre">${nombre}</p>
                    <p data-talla="${talla}" class="talla">Talla: ${talla}</p>
                    <div class="cantidad">Cantidad: <input type="number" min="1" max="${max}" value="1"></div>
                    <p>Precio: ${precio}</p>
                </div>
                <i class="fa-regular fa-trash-can" data-id="${idProducto}" data-talla="${talla}"></i>
            </div>`;
}
