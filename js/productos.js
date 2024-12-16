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
    document.querySelectorAll(".producto .fa-regular.fa-heart").forEach(corazon => corazon.addEventListener("click", ()=>{
        comprobarSesion();
        const corazonFondo = corazon.closest(".producto").querySelector(".fa-solid");
        corazonFondo.style.display = window.getComputedStyle(corazonFondo).display == "none" ? "block" : "none";
    }));
    document.querySelectorAll(".producto .anadirCesta").forEach(botonAnadir => botonAnadir.addEventListener("click", ()=>{
        comprobarSesion();
    }));
    function comprobarSesion(){
        if (!usuario) {
            window.location.href = './register.php';
        }
    }
});