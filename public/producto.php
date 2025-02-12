<?php
if (empty($_GET['id'])) {
  header("Location: ./productos");
  exit();
}
include_once("./partials/header.php");
$statement = $conne->prepare("SELECT * FROM productos WHERE ID = :id AND activo = 1");
$statement->bindParam(":id", $_GET["id"]);
$statement->execute();
$producto = $statement->fetch(PDO::FETCH_ASSOC);
if (!$producto) {
  echo "Producto no encontrado";
  exit();
}
$likedProductIds = [];
    if (!empty($_SESSION["user"])) {
        $statement = $conne->prepare("SELECT ProductoID FROM Likes WHERE Usuario = :usuario AND ProductoID = :producto");
        $statement->bindParam(":usuario", $_SESSION["user"]);
        $statement->bindParam(":producto", $_GET["id"]);
        $statement->execute();
        $likedProductIds = $statement->fetchAll(PDO::FETCH_COLUMN);
    }
function tieneLike($idProducto)
{
  global $likedProductIds;
  return in_array($idProducto, $likedProductIds) ? "block" : "none";
}
?>
<link rel="stylesheet" href="./css/common.css">
<link rel="stylesheet" href="./css/producto.css">
<script type="module">
  import { habilitarCarrouseles, habilitarLikes, habilitarBotonesCompra } from "./js/utils.js";
  window.addEventListener("load", () => {
    habilitarCarrouseles();
    habilitarLikes();
    habilitarBotonesCompra();
  }); 
</script>
<main class="productos">
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
            <img src="./img/<?= trim($imagen) ?>" alt="Imagen del producto <?= $producto["Nombre"]?> de <?= $producto["Sexo"]?>">
        <?php }
        ?>
      </div>
      <i class="fa-solid fa-angle-left"></i>
    </div>
    <div class="detalle-producto">
      <div class="info-producto">
        <p class="nombre"><?= mb_convert_case($producto["Nombre"] . "|" . $producto["Sexo"], MB_CASE_TITLE) ?></p>
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
</main>
<?php
include_once("./partials/footer.php");
?>