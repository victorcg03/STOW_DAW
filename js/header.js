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
    document.querySelectorAll("header i").forEach(el => el.addEventListener("click", () => {
        let visible = document.querySelector(`.visible:not(.${el.dataset.menu})`);
        if (visible) {
            visible.classList.remove("visible");
        }
        setTimeout(() => {
            document.querySelector(`.${el.dataset.menu}`).classList.toggle("visible");
        }, visible ? 400 : 0);
    }));
    document.querySelector("main").addEventListener("click", () => {
        let menu = document.querySelector(".visible");
        if (menu) {
            menu.classList.remove("visible");
        }
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
});