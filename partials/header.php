<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>STOW</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cade5ed75a.js" crossorigin="anonymous"></script>
    <script src="../js/header.js"></script>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/reset.css">
</head>
<body>   
    <header>
        <i class="fa-solid fa-bars"></i>
        <div class="central">
            <a href="./productos.php">Productos</a>
            <a href="./">STOW</a>
            <a href="nosotros.php">Sobre nosotros</a>
        </div>
        <nav>
            <i class="fa-solid fa-user"></i>
            <i class="fa-solid fa-search"></i>
            <i class="fa-solid fa-shopping-cart"></i>
        </nav>
        <div class="menu-izquierdo">
            <ul>
                <li>
                    <a href="./productos.php/?sexo=hombre">Hombre</a>
                    <ul>
                        <li><a href="./productos.php/?sexo=hombre&tipo=sudaderas">Sudaderas</a></li>
                        <li><a href="./productos.php/?sexo=hombre&tipo=camisetas">Camisetas</a></li>
                        <li><a href="./productos.php/?sexo=hombre&tipo=gorros">Gorros</a></li>
                    </ul>
                </li>
                <li>
                    <a href="./productos.php/?sexo=mujer">Mujer</a>
                    <ul>
                        <li><a href="./productos.php/?sexo=mujer&tipo=sudaderas">Sudaderas</a></li>
                        <li><a href="./productos.php/?sexo=mujer&tipo=camisetas">Camisetas</a></li>
                        <li><a href="./productos.php/?sexo=mujer&tipo=gorros">Gorros</a></li>
                    </ul>
                </li>
                <li>
                    <label for="idioma">Idioma</label> <select name="idioma" id="idioma">
                        <option value="es">Español</option>
                        <option value="en">Ingles</option>
                    </select>
                </li>
                <li>
                    <a href=".contacto.php">Contacto</a>
                </li>
                <li>
                    <a href="./ayuda.php">Ayuda</a>
                </li>
                <li><a href="./opiniones.php">Opiniones</a></li>
            </ul>
        </div>
        <div class="carrito">
            
        </div>
        <div class="busqueda">
            <input type="search" name="search" id="search" placeholder="Buscar...">
        </div>
    </header>
