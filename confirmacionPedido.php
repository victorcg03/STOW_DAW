<?php
  require_once "./partials/header.php";
  $statementDetalle = $conne->prepare("SELECT * FROM cabecerapedidos WHERE PedidoID = :idPedido");
  $statementDetalle->bindParam(":idPedido", $_GET["idPedido"]);
  $statementDetalle->execute();
  $pedido = $statementDetalle->fetch(PDO::FETCH_ASSOC);
  if (count($pedido) == 0) {
    header("Location: ./");
    return;
  }
  $idPedido = $pedido["PedidoID"];
?>
<link rel="stylesheet" href="./css/pedidoProcesado.css">
  <main>
  <div class="pedidoProcesado">
    <h1>Pedido procesado</h1>
    <p>Gracias por su compra</p>
    <p>En breve recibirá un correo con los detalles de su pedido</p>
    <p>El número de pedido es: <?= $idPedido ?></p>
  </div>
  </main>
<?php
  require_once "./partials/footer.php";
?>