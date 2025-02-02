<?php
include_once("./partials/header.php");
$likedProductIds = [];
if (!empty($_SESSION["user"])) {
    $statement = $conne->prepare("SELECT ProductoID FROM Likes WHERE Usuario = :usuario");
    $statement->bindParam(":usuario", $_SESSION["user"]);
    $statement->execute();
    $likedProductIds = $statement->fetchAll(PDO::FETCH_COLUMN);
}
function tieneLike($idProducto)
{
    global $likedProductIds;
    return in_array($idProducto, $likedProductIds) ? "block" : "none";
}
?>
<link rel="stylesheet" href="./css/index.css">
<script type="module" src="./js/index.js"></script>
<main>
    <div class="banner">
    </div>
    <h2>ENVIOS DE 5 A 7 DIAS</h2>
    <div class="productos-anunciados">
        <?php
        try {
            $statement = $conne->prepare("SELECT * FROM Productos ORDER BY RAND() LIMIT 4");
            $statement->execute();
            $productos = $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
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
            </div>
        <?php }
        ?>
    </div>
    <h2><a href="./productos">Ver catálogo</a></h2>
    <div class="oferta">
        <p>Con tu primera compra, disfruta de un <span>25%</span> de descuento</p>
    </div>
</main>
<?php include_once("./partials/footer.php");
?>