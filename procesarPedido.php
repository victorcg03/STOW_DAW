<?php
$error = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["datosPedido"])) {
    require_once "./database.php";
    $idPedido = uniqid("P");
    $datosPedido = json_decode($_POST["datosPedido"], true);
    $costes = $datosPedido["costes"];
    $productosPedido = $datosPedido["productos"];
    $user = $datosPedido["user"];
    $_POST["datosPedido"] = null;
    try {
        $statementCabecera = $conne->prepare("INSERT INTO CabeceraPedidos VALUES (:idPedido, :correoUsuario, NOW(), :subtotal, :envio, :iva, :total)");
        $statementCabecera->bindParam(":idPedido", $idPedido);
        $statementCabecera->bindParam(":correoUsuario", $user);
        $statementCabecera->bindParam(":subtotal", $costes["subtotal"]);
        $statementCabecera->bindParam(":envio", $costes["envio"]);
        $statementCabecera->bindParam(":iva", $costes["iva"]);
        $total = $costes["subtotal"] + $costes["envio"] + $costes["iva"];
        $statementCabecera->bindParam(":total", $total);
        if ($statementCabecera->execute()) {
            $statementDetalle = $conne->prepare("INSERT INTO cuerpopedidos VALUES (:idPedido, :idProducto, :talla, :cantidad, :precio)");
            foreach ($productosPedido as $producto) {
                $statementDetalle->bindParam(":idPedido", $idPedido);
                $statementDetalle->bindParam(":idProducto", $producto["idProducto"]);
                $statementDetalle->bindParam(":talla", $producto["talla"]);
                $statementDetalle->bindParam(":cantidad", $producto["cantidad"]);
                $statementDetalle->bindParam(":precio", $producto["precio"]);
                if (!$statementDetalle->execute()) {
                    $error = "Ha habido un error al crear el detalle del pedido en la BBDD";
                    break;
                }
            }
        } else {
            $error = "Ha habido un error al crear la cabecera del pedido en la BBDD";
        }
    } catch (PDOException $e) {
        $error = "Ha habido un error al crear el pedido en la BBDD: " . $e->getMessage();
    }
    if ($error == null) {
        $mail = mailTemplate($productosPedido, $costes);
        require_once 'sendmail.php';
        enviarCorreo($user, "Pedido " . $idPedido, $mail["html"], $mail["imagenes"]);
        exit(header("Location: ./confirmacionPedido?idPedido=" . $idPedido));
    }
} else {
    header("Location: ./");
    return;
}
?>
<link rel="stylesheet" href="./css/pedidoProcesado.css">
<main>
    <?php
    if ($error) { ?>
        <div class="error">
            <h1>ERROR</h1>
            <p><?= $error ?><br>Contacte con soporte</p>
        </div>
    <?php }?>
</main>
<?php
    require_once "./partials/footer.php";
?>


<?php
function mailTemplate($productos, $costes)
{
    $subtotal = $costes["subtotal"];
    $iva = $costes["iva"];
    $envio = $costes["envio"];
    $total = $subtotal + $iva + $envio;
    $infoProds = productosMail($productos);
    $html = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Pedido recibido</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    color: #000 !important;
                    padding: 20px;
                }
                h1{
                    width:100%;
                    text-align:center;
                }
                .pedido {
                    background-color: #ffffff;
                    border: 1px solid #ddd;
                    border-radius: 10px;
                    padding: 20px;
                    max-width: 600px;
                    margin: 0 auto;
                }
                .producto {
                    display: flex;
                    margin-bottom: 15px;
                    border-bottom: 1px solid #ddd;
                    padding-bottom: 10px;
                }
                .producto img {
                    width: 90px;
                    height: auto;
                    object-fit: contain;
                    margin-right: 10px;
                }
                .producto .detalle {
                    flex-grow: 1;
                }
                .totales {
                    margin-top: 20px;
                }
                .totales p {
                    margin: 5px 0;
                    font-size: 16px;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="pedido">
                <h1>¡Gracias por tu pedido!</h1>
                <p>Recibimos tu pedido y lo estamos procesando. Aquí tienes el detalle del pedido:</p>
                ' . $infoProds["html"] . '
                <div class="totales">
                    <p>Subtotal pedido: ' . number_format($subtotal, 2) . '€</p>
                    <p>IVA: ' . number_format($iva, 2) . '€</p>
                    <p>Envío: ' . number_format($envio, 2) . '€</p>
                    <p>Total pedido: ' . number_format($total, 2) . '€</p>
                </div>
            </div>
        </body>
        </html>
    ';
    return ["html" => $html, "imagenes" => $infoProds["imagenes"]];
}

function productosMail($productos)
{
    $imgs = [];
    $html = '';
    foreach ($productos as $producto) {
        array_push($imgs, ["src" => "./img/" . $producto["img"], "cid" => $producto["idProducto"]]);
        $html .= '
        <div class="producto">
            <img src=cid:' . $producto["idProducto"] . '>
            <div class="detalle">
                <p class="nombre-producto">' . htmlspecialchars($producto["nombre"]) . '</p>
                <p class="talla-producto">Talla: ' . htmlspecialchars($producto["talla"]) . '</p>
                <p class="unidades-producto">Cantidad: ' . htmlspecialchars($producto["cantidad"]) . '</p>
                <p class="precio-producto">Precio: ' . htmlspecialchars($producto["precio"]) . ' </p>
            </div>
        </div>';
    }
    return ["html" => $html,"imagenes" => $imgs];
}

?>