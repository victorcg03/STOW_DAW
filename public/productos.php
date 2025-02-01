<?php
include_once("./partials/header.php");
try {
    if (!empty($_GET['tipo'])) {
        $statement = $conne->prepare("SELECT * FROM Productos WHERE LOWER(Sexo) = LOWER(:sexo) AND LOWER(ClaseProducto) = LOWER(:tipo)");
        $statement->bindParam(":sexo", $_GET["sexo"]);
        $statement->bindParam(":tipo", $_GET["tipo"]);
        $statement->execute();
        $productos = $statement->fetchAll(PDO::FETCH_ASSOC);
    } else if (!empty($_GET['sexo'])) {
        $statement = $conne->prepare("SELECT * FROM Productos WHERE Sexo = :sexo");
        $statement->bindParam(":sexo", $_GET["sexo"]);
        $statement->execute();
        $productos = $statement->fetchAll(PDO::FETCH_ASSOC);
    } else if (!empty($_GET['search'])) {
        $response = file_get_contents("http://localhost/search.php?search=" . urlencode($_GET['search']));
        $productos = json_decode($response, true);
    } else {
        $statement = $conne->prepare("SELECT * FROM Productos");
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
<script src="./js/productos.js"></script>
<main>
    <div class="barra-busqueda">
        <p>Buscar: </p>
        <form action="./productos">
            <input type="text" name="search" placeholder="Sudadera blanca...">
        </form>
    </div>
    <div id="filtros">
        <form action="./productos">
            <input type="text" name="search" hidden value="<?= !empty($_GET["search"]) ? $_GET["search"] : "" ?>">
            <div class="filtro">
                <label for="sexo">Sexo: </label>
                <select name="sexo" id="sexo">
                    <option>Seleccionar</option>
                    <option value="hombre">Hombre</option>
                    <option value="mujer">Mujer</option>
                </select>
            </div>
            <div class="filtro">
                <label for="color">Color: </label>
                <select name="color" id="color">
                    <option>Seleccionar</option>
                    <?php
                    $colores = array_unique(array_column($productos, 'Color'));
                    sort($colores);
                    foreach ($colores as $color) { ?>
                        <option value="<?= $color ?>"><?= $color ?></option>
                    <?php   }
                    ?>
                </select>
            </div>
            <div class="filtro">
                <label for="tipo">Tipo de prenda: </label>
                <select name="tipo" id="tipo">
                    <option>Seleccionar</option>
                    <?php
                    $tipos = array_unique(array_column($productos, 'ClaseProducto'));
                    sort($tipos);
                    foreach ($tipos as $tipo) { ?>
                        <option value="<?= $tipo ?>"><?= $tipo ?></option>
                    <?php   }
                    ?>
                </select>
            </div>
            <div class="filtro">
                <label for="talla">Talla: </label>
                <select name="talla" id="talla">
                    <option>Seleccionar</option>
                    <?php
                    $tallasDisponibles = [];
                    foreach ($productos as $producto) {
                        $stock = $producto['Stock'];

                        $pares = explode(', ', $stock);

                        foreach ($pares as $par) {
                            list($talla, $cantidad) = explode(':', $par);
                            if ((int)$cantidad > 0) {
                                $tallasDisponibles[] = $talla;
                            }
                        }
                    }
                    $ordenTallas = ['XS', 'S', 'M', 'L', 'XL'];
                    usort($tallasDisponibles, function ($a, $b) use ($ordenTallas) {
                        return array_search($a, $ordenTallas) - array_search($b, $ordenTallas);
                    });
                    foreach (array_unique($tallasDisponibles) as $talla) { ?>
                        <option value="<?= $talla ?>"><?= $talla ?></option>
                    <?php }
                    ?>
                </select>
            </div>
        </form>
    </div>
    <p class="productos-encontrados">Productos encontrados: <?= count($productos) ?></p>
    <div class="productos">
        <?php
        foreach ($productos as $producto) { ?>
            <div class="producto" data-id="<?= $producto['ID'] ?>">
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