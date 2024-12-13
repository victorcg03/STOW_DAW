window.addEventListener('load', ()=>{
    document.querySelector('.fa-bars').addEventListener("click", ()=>{
        esconderMenus("menu-izquierdo");
        document.querySelector('.menu-izquierdo').classList.toggle('visible');
    });
    document.querySelector('.fa-shopping-cart').addEventListener("click", ()=>{
        esconderMenus("carrito");
        document.querySelector('.carrito').classList.toggle('visible');
    });
    document.querySelector('.fa-search').addEventListener("click", ()=>{
        esconderMenus("busqueda");
        document.querySelector('.busqueda').classList.toggle('visible');
    });
    document.querySelector("main").addEventListener("click", esconderMenus);
});
function esconderMenus(menu){
    document.querySelectorAll(".visible").forEach(activo =>{
        if (!activo.classList.contains(menu)) {
            activo.classList.remove("visible");
        }
    });
}