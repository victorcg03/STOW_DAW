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