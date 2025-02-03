export function habilitarCarrouseles() {
    document.querySelectorAll(".fa-angle-right").forEach(rightArrow => {
        const leftArrow = rightArrow.closest(".imagenes")?.querySelector(".fa-angle-left");
        const carrousel = rightArrow.closest(".imagenes")?.querySelector(".carrousel");
        const images = carrousel?.querySelectorAll("img");
        if (!leftArrow || !carrousel || !images) return;

        let currentIndex = 0;
        let startX = 0;
        let endX = 0;

        function actualizarCarrusel() {
            carrousel.style.transform = `translateX(-${currentIndex * 100}%)`;
        }

        function moverDerecha() {
            if (currentIndex < images.length - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            actualizarCarrusel();
        }

        function moverIzquierda() {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = images.length - 1;
            }
            actualizarCarrusel();
        }

        rightArrow.addEventListener("click", moverDerecha);
        rightArrow.addEventListener("touchend", moverDerecha);

        leftArrow.addEventListener("click", moverIzquierda);
        leftArrow.addEventListener("touchend", moverIzquierda);

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



        // Mejora de transiciones en CSS
        carrousel.style.transition = "transform 0.3s ease-in-out";
    });
}

export function habilitarLikes() {
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
}
export function habilitarBotonesCompra() {
    document.querySelectorAll(".producto .anadirCesta").forEach(botonAnadir =>
        botonAnadir.addEventListener("click", async () => {
            if (await comprobarSesion()) {
                document.querySelector(".carrito .mensaje").classList.add("display-none");
                document.querySelector(".carrito .info-carrito").classList.remove("display-none");
                const cookie = getCookie("productosCarrito");
                let productosCarritoCookie = cookie ? JSON.parse(cookie) : [];
                const producto = botonAnadir.closest(".producto");
                const talla = producto.querySelector(".tallaProducto").value;
                const stock = producto.querySelector(`.tallaProducto > option[value="${talla}"]`).dataset.stock;
                const id = producto.dataset.id;

                const inputSelector = `#carrito .producto[data-talla="${talla}"][data-id="${id}"] input`;
                const input = document.querySelector(inputSelector);
                const productoEncontrado = productosCarritoCookie.find(producto => producto.idProducto == id && producto.talla == talla);

                if (productoEncontrado) {

                    if (productoEncontrado.cantidad < stock) {
                        productoEncontrado.cantidad++;
                        input.value = productoEncontrado.cantidad;
                        input.setAttribute("value", productoEncontrado.cantidad);
                    }
                } else {
                    const imgSRC = producto.querySelectorAll("img")[0].src.split("/");
                    const img = imgSRC[imgSRC.length - 1];
                    const nombre = producto.querySelector(".nombre").innerText;
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

                    document.querySelector("#carrito").innerHTML += productoCestaPlantilla(talla, id, img, nombre, stock, precio, 1);
                }

                document.cookie = `productosCarrito=${encodeURIComponent(JSON.stringify(productosCarritoCookie))}; path=/; max-age=3600`;
                document.querySelector(".carrito").classList.add("visible");
                actualizarInfoCesta(productosCarritoCookie);
            }
        }));
}