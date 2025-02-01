<?php
  require_once "comprobarSesionIniciada.php";
  if ($SESION_INICIADA) {
    require_once "../../database.php";
    if (!$conne) {
      echo json_encode(["error" => "No se pudo conectar a la base de datos"]);
      die();
    } 
    $completados = $_GET["completados"];
    $pedidos = [];
    try{
      if ($completados != 'null') {
        $statementCabecera = $conne->prepare("SELECT * FROM cabecerapedidos WHERE completado = :completado");
        $statementCabecera->bindParam(":completado", $completados);
      } else {
        $statementCabecera = $conne->prepare("SELECT * FROM cabecerapedidos");
      }
      $statementCabecera->execute();
      $cabecerasPedidos = $statementCabecera->fetchAll(PDO::FETCH_ASSOC);
      if ($cabecerasPedidos){
        foreach ($cabecerasPedidos as $cabeceraPedido) {
          $statementCuerpo = $conne->prepare("SELECT * FROM cuerpopedidos WHERE PedidoID = :PedidoID");
          $statementCuerpo->bindParam(":PedidoID", $cabeceraPedido["PedidoID"]);
          $statementCuerpo->execute();
          $lineasPedido = $statementCuerpo->fetchAll(PDO::FETCH_ASSOC);
          $pedido = [
            "cabecera" => $cabeceraPedido,
            "lineas" => $lineasPedido
          ];
          array_push($pedidos, $pedido);
        }
      }
    } catch (Exception $e) {
      echo json_encode(["error" => true, "msg" => "Error al obtener los pedidos" . $e->getMessage()]);
      die();
    }
    echo json_encode(["pedidos" => $pedidos]);
    die();
  }
?>