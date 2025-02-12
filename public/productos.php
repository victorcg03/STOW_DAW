<?php
require_once("./partials/header.php");
try {
    if (!empty($_GET['tipo'])) {
        $statement = $conne->prepare("SELECT * FROM Productos WHERE LOWER(Sexo) = LOWER(:sexo) AND LOWER(ClaseProducto) = LOWER(:tipo) AND activo = 1");
        $statement->bindParam(":sexo", $_GET["sexo"]);
        $statement->bindParam(":tipo", $_GET["tipo"]);
        $statement->execute();
        $productos = $statement->fetchAll(PDO::FETCH_ASSOC);
        $subtitulo = ($_GET["tipo"] == "gorro" ? "Estos son todos los " : "Estas son todas las ") . $_GET["tipo"] . "s de " . $_GET["sexo"] . " de nuestro catálogo: ";
    } else if (!empty($_GET['sexo'])) {
        $statement = $conne->prepare("SELECT * FROM Productos WHERE Sexo = :sexo AND activo = 1");
        $statement->bindParam(":sexo", $_GET["sexo"]);
        $statement->execute();
        $productos = $statement->fetchAll(PDO::FETCH_ASSOC);
        $subtitulo = "Estos son todos los productos de " . $_GET["sexo"] . " de nuestro catálogo: ";
    } else if (!empty($_GET['search'])) {
        require_once "./search.php";
        $subtitulo = "Estos son los resultados de la búsqueda '" . $_GET["search"] . "': ";
    } else {
        $statement = $conne->prepare("SELECT * FROM Productos where activo = 1");
        $statement->execute();
        $productos = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    $likedProductIds = [];
    if (!empty($_SESSION["user"])) {
        $statement = $conne->prepare("SELECT ProductoID FROM Likes WHERE Usuario = :usuario");
        $statement->bindParam(":usuario", $_SESSION["user"]);
        $statement->execute();
        $likedProductIds = $statement->fetchAll(PDO::FETCH_COLUMN);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
function tieneLike($idProducto)
{
    global $likedProductIds;
    return in_array($idProducto, $likedProductIds) ? "block" : "none";
}
?>
<link rel="stylesheet" href="./css/productos.css">
<script type="module" src="./js/productos.js"></script>
<main>
    <h1>CATALOGO</h1>
    <div class="barra-busqueda">
        <p>Buscar: </p>
        <form action="./productos">
            <input type="text" name="search" placeholder="Sudadera blanca..." value="<?= !empty($_GET['search']) ? $_GET['search'] : "" ?>">
        </form>
    </div>
    <h2><?= !empty($subtitulo) ? $subtitulo : "Estos son todos los productos de nuestro catálogo:"?></h2>
    <p class="productos-encontrados">Productos encontrados: <?= count($productos) ?></p>
    <div class="productos">
        <?php
        foreach ($productos as $producto) { ?>
            <a class="producto" data-id="<?= $producto['ID'] ?>" href="./producto?id=<?= $producto['ID'] ?>">
                <span class="fa-layers fa-fw">
                    <i class="fa-regular fa-heart"></i>
                    <i class="fa-solid fa-heart" style="display:<?= tieneLike($producto['ID']) ?>"></i>
                </span>
                <div class="imagenes">
                    <i class="fa-solid fa-angle-right"></i>
                    <div class="carrousel">
                        <?php
                        $imagenes = explode(",", $producto['Imagenes']);
                        foreach ($imagenes as $imagen) { ?>
                            <img src="./img/<?= trim($imagen) ?>" alt="Imagen del producto <?= $producto["Nombre"]?> de <?= $producto["Sexo"]?>">
                        <?php }
                        ?>
                    </div>
                    <i class="fa-solid fa-angle-left"></i>
                </div>
                <div class="detalle-producto">
                    <div class="info-producto">
                        <p class="nombre"><?= $producto["Nombre"] . "|" . $producto["Sexo"] ?></p>
                        <p class="precio"><?= $producto["Precio"] ?>€</p>
                    </div>
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
            </a>
        <?php }
        ?>
    </div>
</main>
<?php include_once("./partials/footer.php");
?>

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