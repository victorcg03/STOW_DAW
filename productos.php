<?php
include_once("./partials/header.php");

if (!empty($_GET['tipo'])) {
    $statement = $conne->prepare("SELECT * FROM productos WHERE LOWER(Sexo) = LOWER(:sexo) AND LOWER(ClaseProducto) = LOWER(:tipo)");
    $statement->bindParam(":sexo", $_GET["sexo"]);
    $statement->bindParam(":tipo", $_GET["tipo"]);
    $statement->execute();
    $productos = $statement->fetchAll();
} else if (!empty($_GET['sexo'])) {
    $statement = $conne->prepare("SELECT * FROM productos WHERE Sexo = :sexo");
    $statement->bindParam(":sexo", $_GET["sexo"]);
    $statement->execute();
    $productos = $statement->fetchAll();
} else if (!empty($_GET['search'])) {
    $response = file_get_contents("http://localhost/search.php?search=" . urlencode($_GET['search']));
    $productos = json_decode($response, true);
} else {
    $statement = $conne->prepare("SELECT * FROM productos");
    $statement->execute();
    $productos = $statement->fetchAll();
}
$likedProductIds = [];
if (!empty($_SESSION["user"])) {
    $statement = $conne->prepare("SELECT ProductoID FROM Likes WHERE Usuario = :usuario");
    $statement->bindParam(":usuario", $_SESSION["user"]);
    $statement->execute();
    $likedProductIds = $statement->fetchAll(PDO::FETCH_COLUMN);
}
function tieneLike($idProducto){
    global $likedProductIds;
    return in_array($idProducto, $likedProductIds) ? "block" : "none";
}
?>
<link rel="stylesheet" href="./css/productos.css">
<script src="./js/productos.js"></script>
<main>
    <div class="barra-busqueda">
        <p>Buscar: </p>
        <form action="./productos.php">
            <input type="text" name="search" placeholder="Sudadera blanca...">
        </form>
    </div>
    <p class="productos-encontrados">Productos encontrados: <?= count($productos) ?></p>
    <div class="productos">
        <?php
        foreach ($productos as $producto) { ?>
            <div class="producto" data-id="<?= $producto['ID'] ?>">
                <span class="fa-layers fa-fw">
                    <i class="fa-regular fa-heart"></i>
                    <i class="fa-solid fa-heart" style="display:<?=  tieneLike($producto['ID'])?>"></i>
                </span>
                <div class="imagenes">
                    <i class="fa-solid fa-angle-right"></i>
                    <div class="carrousel">
                        <?php
                        $imagenes = explode(",", $producto['Imagenes']);
                        foreach ($imagenes as $imagen) { ?>
                            <img src="./img/<?= trim($imagen) ?>">
                        <?php }
                        ?>
                    </div>
                    <i class="fa-solid fa-angle-left"></i>
                </div>
                <div class="info-producto">
                    <p class="nombre"><?= $producto["Nombre"] . "|" . $producto["Sexo"] ?></p>
                    <p class="precio"><?= $producto["Precio"] ?>€</p>

                    <div class="comprar">
                        <button class="anadirCesta">Añadir a la cesta</button>
                        <select class="tallaProducto" name="talla">
                            <?php
                            $tallas = explode(",", $producto['Stock']);
                            foreach ($tallas as $talla) {
                                $partes = explode(":", $talla); ?>

                                <option data-stock="<?= $partes[1] ?>" class="notranslate" value="<?= $partes[0] ?>" <?= $partes[1] == 0 ? "disabled" : "" ?>><?= trim($partes[0]) ?></option>
                            <?php  }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        <?php }
        ?>
    </div>
</main>
<!-- 
<div class="producto">
            <div class="imagenes">
                <i class="fa-solid fa-angle-right"></i>
                <div class="carrousel">
                    <img src="./img/img1.jpg" alt="">
                    <img src="./img/img2.webp" alt="">
                    <img src="./img/img3.jpg" alt="">
                </div>
                <i class="fa-solid fa-angle-left"></i>
            </div>
            <div class="info-producto">
                <p class="nombre">Sudadera Negra Oversize ewrwer wer wer wer</p>
                <p class="precio">14.99€</p>

                <div class="comprar">
                    <button>Añadir a la cesta</button>
                    <select id="talla">
                        <option value="xs" disabled>XS</option>
                        <option value="s">S</option>
                        <option value="m">M</option>
                        <option value="l">L</option>
                        <option value="xl">XL</option>
                    </select>
                </div>
            </div>
        </div> -->