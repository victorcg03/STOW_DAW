<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "./database.php";
$titulo = "STOW - " . strtoupper(basename($_SERVER['PHP_SELF'], ".php"));
if ($titulo == "STOW - INDEX") {
    $titulo = "STOW - INICIO";
}
if (isset($_GET['id'])) {
    $statement = $conne -> prepare("SELECT nombre FROM productos WHERE id = :id");
    $statement -> bindParam(":id", $_GET['id']);
    $statement -> execute();
    $producto = $statement -> fetch();
    $titulo = "STOW - " . strtoupper($producto['nombre']);
}
if (isset($_GET['sexo'])) {
    $titulo = "STOW - CATÁLOGO DE " . strtoupper($_GET['sexo']);
}
if (isset($_GET['tipo'])) {
    $titulo = "STOW - " . strtoupper($_GET['tipo']) . "S DE " . strtoupper($_GET['sexo']);
}
if (isset($_GET['search'])) {
    $titulo = "STOW - RESULTADOS BUSQUEDA DE " . $_GET['search'];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title class="notranslate"><?= $titulo ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cade5ed75a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/common.css">
    <meta name="description" content="Descubre en STOW las mejores sudaderas, camisetas y gorros para hombre y mujer, con diseños originales y calidad premium a un precio justo.">
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'es',
                includedLanguages: 'en,es,fr,de,it'
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script src="./js/header.js"></script>
</head>

<body>
    <header>
        <i class="fa-solid fa-bars" data-menu="menu-izquierdo"></i>
        <div class="central">
            <a class="notranslate" href="./">STOW</a>
        </div>
        <nav>
            <i class="fa-solid fa-user" data-menu="usuario"></i>
            <i class="fa-solid fa-search" data-menu="busqueda"></i>
            <i class="fa-solid fa-shopping-cart" data-menu="carrito"></i>
        </nav>
        <div class="menu-izquierdo menu">
            <ul>
                <li><a href="./productos">Productos</a></li>
                <li>
                    <ul>
                        <li>
                            <a href="./productos?sexo=hombre">Hombre</a>
                            <ul>
                                <li><a href="./productos?sexo=hombre&tipo=sudadera">Sudaderas</a></li>
                                <li><a href="./productos?sexo=hombre&tipo=camiseta">Camisetas</a></li>
                                <li><a href="./productos?sexo=hombre&tipo=gorro">Gorros</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="./productos?sexo=mujer">Mujer</a>
                            <ul>
                                <li><a href="./productos?sexo=mujer&tipo=sudadera">Sudaderas</a></li>
                                <li><a href="./productos?sexo=mujer&tipo=camiseta">Camisetas</a></li>
                                <li><a href="./productos?sexo=mujer&tipo=gorro">Gorros</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <label>Idioma</label>
                    <div id="google_translate_element"></div>
                </li>
                <li>
                    <a href="./contacto">Contacto</a>
                </li>
                <li>
                    <a href="./nosotros">Sobre nosotros</a>
                </li>
                <li>
                    <a href="./opiniones">Opiniones</a>
                </li>
            </ul>
        </div>
        <div class="carrito menu">
            <div id="carrito"></div>
            <div class="mensaje">No hay productos en el carrito</div>
            <div class="info-carrito display-none">
                <p>Unidades: <span id="unidades"></span></p>
                <p>Subtotal (IVA y envio NO incluido): <span id="total"></span>€</p>
                <a href="./carrito">Ver artículos en tu cesta</a>
            </div>
        </div>
        <div class="busqueda menu">
            <form action="./productos" method="GET">
                <input type="search" name="search" id="search" placeholder="Buscar...">
            </form>
            <div class="resultados">

            </div>
        </div>
        <div class="usuario menu">
            <?php
            if (empty($_SESSION['user'])) { ?>
                <div>
                    <a href="./login">Iniciar sesión</a>
                    <br>
                    ó
                    <br>
                    <a href="./register">Registrarse</a>
                </div>
            <?php  } else { ?>
                <div class="user">
                    <a href="./likes">Ver likes</a>
                    <a href="./pedidos">Ver pedidos</a>
                    <a href="./logout">Cerrar sesión</a>
                </div>
            <?php } ?>
        </div>
    </header>