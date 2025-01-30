<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title class="notranslate">STOW</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cade5ed75a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/carrito.css">
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'es',
                includedLanguages: 'en,es,fr,de,it'
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script src="./js/carrito.js"></script>

</head>
<body>
    <div id="fondo"></div>
    <header>
        <a href="./">STOW</a>
    </header>
    <main>
        <div class="izquierda">
            <div id="mensaje">
                <p>No hay productos en la cesta...</p>
                <a href="./productos">Explorar artículos</a>
            </div>
        </div>
        <div class="derecha">
            <h2>Resumen:</h2>
            <p>Subtotal: <span id="subtotal"></span>€</p>
            <p>IVA: <span id="iva"></span>€</p>
            <p>Envio: <span id="envio"></span>€</p>
            <p>Total: <span id="total"></span>€</p>
            <button class="realizar-pedido">Realizar pedido</button>
        </div>
    </main>
</body>
</html>