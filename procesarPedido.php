<?php
require_once "./partials/header.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["datosPedido"]) && !empty($_SESSION["user"])) {
    $error = null;

    $datosPedido = json_decode($_POST["datosPedido"], true);
    $costes = $datosPedido["costes"];
    $productosPedido = $datosPedido["productos"];
    $idPedido = uniqid("P");
    $statementCabecera = $conne->prepare("INSERT INTO CabeceraPedidos VALUES (:idPedido, :correoUsuario, NOW(), :subtotal, :envio, :iva, :total)");
    $statementCabecera->bindParam(":idPedido", $idPedido);
    $statementCabecera->bindParam(":correoUsuario", $_SESSION["user"]);
    $statementCabecera->bindParam(":subtotal", $costes["subtotal"]);
    $statementCabecera->bindParam(":envio", $costes["envio"]);
    $statementCabecera->bindParam(":iva", $costes["iva"]);
    $total = $costes["subtotal"] + $costes["envio"] + $costes["iva"];
    $statementCabecera->bindParam(":total", $total);

    try {
        if ($statementCabecera->execute()) {
            $statementDetalle = $conne->prepare("INSERT INTO cuerpopedidos VALUES (:idPedido, :idProducto, :talla, :cantidad, :precio)");

            foreach ($productosPedido as $producto) {
                $statementDetalle->bindParam(":idPedido", $idPedido);
                $statementDetalle->bindParam(":idProducto", $producto["idProducto"]);
                $statementDetalle->bindParam(":talla", $producto["talla"]);
                $statementDetalle->bindParam(":cantidad", $producto["cantidad"]);
                $statementDetalle->bindParam(":precio", $producto["precio"]);
                if (!$statementDetalle->execute()) {
                    $error = "Ha habido un error al crear el detalle del pedido";
                    break;
                }
            }
        } else {
            echo "error";
        }
    } catch (PDOException $e) {
        $error = "Ha habido un error al crear la cabecera del pedido: " . $e->getMessage();
        echo $error;
    } finally {
        $statementCabecera->close();
        $statementDetalle->close();
        $conne->close();
    }
} else {
    header("Location: ./");
    exit();
}

require_once "./partials/footer.php";
