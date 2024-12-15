window.addEventListener('load', ()=>{
    document.querySelector("#google_translate_element .skiptranslate").childNodes.forEach(nodo => {
        if (nodo.nodeName === "#text" || nodo.nodeName === "SPAN") {
            nodo.remove();
        }
    });
    verPass.addEventListener("click", ()=>{
        verPass.classList.toggle("fa-eye");
        verPass.classList.toggle("fa-eye-slash");
        password.type = password.type == "text" ? "password" : "text";
    });
})