<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: ./login');
  exit();
}
require_once "./partials/header.php";
$statementCabeceras = $conne->prepare("SELECT * FROM cabecerapedidos WHERE CorreoUsuario = :usuario");
$statementCabeceras->bindParam(":usuario", $_SESSION['user']);
$statementCabeceras->execute();
$cabecerasPedidos = $statementCabeceras->fetchAll();


?>
<link rel="stylesheet" href="./css/pedidos.css">
<script src="./js/pedidos.js"></script>
<main>
  <?php
  if (count($cabecerasPedidos) == 0) {
    echo "<h1>No has realizado ningún pedido todavía :(</h1>";
    echo "<a href='./productos' class='verProductos'>Explorar productos</a>";
  } else { ?>
    <h1>Pedidos realizados:</h1>
    <?php
    foreach ($cabecerasPedidos as $cabeceraPedido) {
      echo "<details>";
      echo "<summary>Pedido " . $cabeceraPedido["PedidoID"] . ($cabeceraPedido["Completado"] == "True" ? " - Completado" : " - En curso") . "</summary>";
      echo "<div>";
      echo "<p>Fecha: " . $cabeceraPedido["FechaPedido"] . "</p>";
      echo "<p>Productos:</p>";
      $statementDetalles = $conne->prepare("SELECT * FROM cuerpopedidos WHERE PedidoID = :idPedido");
      $statementDetalles->bindParam(":idPedido", $cabeceraPedido['PedidoID']);
      $statementDetalles->execute();
      $detallesPedido = $statementDetalles->fetchAll();
      foreach ($detallesPedido as $detallePedido) {
        $statementProducto = $conne->prepare("SELECT * FROM productos WHERE ID = :idProducto");
        $statementProducto->bindParam(":idProducto", $detallePedido["ProductoID"]);
        $statementProducto->execute();
        $producto = $statementProducto->fetchAll()[0];
    ?>
        <div class="producto">
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
          <div class="detalle">
            <p><?= $producto["Nombre"] ?></p>
            <p>Talla: <?= $detallePedido["ProductoTalla"] ?></p>
            <p>Cantidad: <?= $detallePedido["Cantidad"] ?></p>
            <p>Precio unitario: <?= number_format($detallePedido["Precio"], 2) ?>€</p>
            <p>Subtotal producto: <?= number_format($detallePedido["Precio"] * $detallePedido["Cantidad"], 2) ?>€</p>
          </div>
        </div>
    <?php }
      echo "<p>Subtotal: " . number_format($cabeceraPedido["SubtotalPedido"], 2) . "€</p>";
      echo "<p>IVA: " . number_format($cabeceraPedido["IvaPedido"], 2) . "€</p>";
      echo "<p>Envio: " . number_format($cabeceraPedido["EnvioPedido"], 2) . "€</p>";
      echo "<p>Total: " . number_format($cabeceraPedido["TotalPedido"], 2) . "€</p>";
      echo "</div>";
      echo "</details>";
    }
    ?>
  <?php  }
  ?>
</main>

<?php
require_once "./partials/footer.php";
?>