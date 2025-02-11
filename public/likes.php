<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: ./login');
  return;
}
require "./partials/header.php";
?>
<main>
  <?php
  $usuario = $_SESSION["user"];
  $statement = $conne->prepare("select * from productos where ID IN (SELECT ProductoID from likes WHERE Usuario=:usuario) AND activo = 1");
  $statement->bindParam(":usuario", $usuario);
  $statement->execute();
  $productos = $statement->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <link rel="stylesheet" href="./css/likes.css">
  <script type="module" src="./js/likes.js"></script>
  <?php
  if (count($productos) == 0) { ?>
    <div class="noProds">
      <h1>No hay productos para mostrar :(</h1>
      <a href="productos">Explorar catálogo</a>
    </div>
  <?php } else { ?>
    <h1>Productos guardados:</h1>
    <div class="productos">
      <?php
      foreach ($productos as $producto) { ?>
        <div class="producto" data-id="<?= $producto['ID'] ?>">
          <span class="fa-layers fa-fw">
            <i class="fa-regular fa-heart"></i>
            <i class="fa-solid fa-heart" style="display:block"></i>
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
        </div>

      <?php  } ?>
    </div>
  <?php }
  ?>
</main>

<?php require "./partials/footer.php"; ?>