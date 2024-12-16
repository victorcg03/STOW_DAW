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
            carrousel.style.transform = `translateX(-${currentIndex*100}%)`;
        });
        leftArrow.addEventListener("click", () => {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = images.length - 1; // Ir a la última imagen
            }
            carrousel.style.transform = `translateX(-${currentIndex*100}%)`;
        });
    });
    document.querySelectorAll(".producto .fa-regular.fa-heart").forEach(corazon => corazon.addEventListener("click", async()=>{
       
        
        const corazonFondo = corazon.closest(".producto").querySelector(".fa-solid");
        const idProducto = corazon.closest(".producto").dataset.id;
        let usuario = await comprobarSesion();
        fetch("saveLike.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                "idProducto" : idProducto, 
                "usuario" : usuario
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
    
    document.querySelectorAll(".producto .anadirCesta").forEach(botonAnadir => botonAnadir.addEventListener("click", ()=>{

    }));
    async function comprobarSesion(){
        let respuesta = await fetch("./verificarSesionIniciada.php");
        let data = await respuesta.json();
        if (!data.loggedIn) {
            window.location.href = "login.php";
        }
        return data.user;
        
    }
});