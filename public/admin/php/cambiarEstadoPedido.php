<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $data = json_decode(file_get_contents("php://input"), true);
  $id = $data["ID_PEDIDO"];
  $completado = $data["pedidoCompletado"] ? "False" : "True";
  require_once "../../database.php";
  if (!$conne) {
    echo json_encode(["error" => "No se pudo conectar a la base de datos"]);
    die();
  }
  try {
    $statement = $conne->prepare("UPDATE cabecerapedidos SET Completado = :completado WHERE PedidoID = :PedidoID");
    $statement->bindParam(":PedidoID", $id);
    $statement->bindParam(":completado", $completado);
    $statement->execute();
    echo json_encode(["msg" => "Pedido actualizado"]);
    die();
  } catch (Exception $e) {
    echo json_encode(["error" => true, "msg" => "Error al actualizar el pedido" . $e->getMessage()]);
    die();
  }
}
