window.addEventListener('load', () => {
    document.querySelector("#google_translate_element .skiptranslate").childNodes.forEach(nodo => {
        if (nodo.nodeName === "#text" || nodo.nodeName === "SPAN") {
            nodo.remove();
        }
    });
    document.querySelectorAll("header i").forEach(el => el.addEventListener("click", ()=>{
        let visible = document.querySelector(`.visible:not(.${el.dataset.menu})`);       
        if (visible) {
            visible.classList.remove("visible");
        }
        setTimeout(() => {
            document.querySelector(`.${el.dataset.menu}`).classList.toggle("visible");
        }, visible ? 400 : 0);
    }));
    document.querySelector("main").addEventListener("click", ()=>{
        document.querySelector(".visible").classList.remove("visible");
    });
});