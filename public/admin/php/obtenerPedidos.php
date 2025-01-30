<?php
  require_once "./comprobarSesionIniciada.php";
  if ($SESION_INICIADA) {
    require_once "../../database.php";
    $completados = $_GET["completados"];
    $pedidos = [];
    if ($completados) {
      $statementCabecera = $conne -> prepare("SELECT * FROM CabeceraPedidos WHERE completado = :completados");
      $statementCabecera -> bindParam(":completados", $completados);
    } else {
      $statementCabecera = $conne -> prepare("SELECT * FROM CabeceraPedidos");
    }
    $statementCabecera -> execute();
    $cabeceras = $statementCabecera -> fetchAll(PDO::FETCH_ASSOC);
    foreach ($cabeceras as $cabecera) {
      $idPedido = $cabecera["PedidoID"];
      $statementLineas = $conne -> prepare("SELECT * FROM cuerpopedidos WHERE PedidoID = :idPedido");
      $statementLineas -> bindParam(":idPedido", $idPedido);
      $statementLineas -> execute();
      $lineas = $statementLineas -> fetchAll(PDO::FETCH_ASSOC);
      $pedido = ["cabecera" => $cabecera, "lineas" => $lineas];
      array_push($pedido);
    }
    echo json_encode($pedidos);
  }
?>