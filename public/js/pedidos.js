window.addEventListener("load", habilitarCarrouseles);
function habilitarCarrouseles(){
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
}