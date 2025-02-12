let path = window.location.pathname
if (path == "/" || path == "/index.php") {
    document.title = "STOW - INICIO";
} else {
    let title = path.split("/").pop().split(".")[0].toUpperCase();
    document.title = `STOW - ${title}`;
}
window.addEventListener('load', ()=>{
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
    verPass.addEventListener("click", ()=>{
        verPass.classList.toggle("fa-eye");
        verPass.classList.toggle("fa-eye-slash");
        password.type = password.type == "text" ? "password" : "text";
    });
})