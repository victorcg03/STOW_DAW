window.addEventListener("load", habilitarCarrouseles);
function habilitarCarrouseles() {
    document.querySelectorAll(".fa-angle-right").forEach(rightArrow => {
        const leftArrow = rightArrow.closest(".imagenes").querySelector(".fa-angle-left");
        const carrousel = rightArrow.closest(".imagenes").querySelector(".carrousel");
        const images = carrousel.querySelectorAll("img");
        let currentIndex = 0;
        let touchStartX = 0;
        let touchEndX = 0;

        // Función para mover el carrusel
        function moveCarrousel() {
            carrousel.style.transform = `translateX(-${currentIndex * 100}%)`;
        }

        // Evento de clic para las flechas
        rightArrow.addEventListener("click", () => {
            if (currentIndex < images.length - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            moveCarrousel();
        });

        leftArrow.addEventListener("click", () => {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = images.length - 1;
            }
            moveCarrousel();
        });

        // Eventos de deslizamiento táctil
        carrousel.addEventListener("touchstart", (e) => {
            touchStartX = e.touches[0].clientX; // Almacenar la posición inicial
            alert("a");
            
        });

        carrousel.addEventListener("touchmove", (e) => {
            touchEndX = e.touches[0].clientX; // Almacenar la posición del toque en movimiento
        });

        carrousel.addEventListener("touchend", () => {
            if (touchStartX - touchEndX > 50) { // Si se deslizó hacia la izquierda
                if (currentIndex < images.length - 1) {
                    currentIndex++;
                } else {
                    currentIndex = 0;
                }
                moveCarrousel();
            } else if (touchEndX - touchStartX > 50) { // Si se deslizó hacia la derecha
                if (currentIndex > 0) {
                    currentIndex--;
                } else {
                    currentIndex = images.length - 1;
                }
                moveCarrousel();
            }
        });
    });
}
