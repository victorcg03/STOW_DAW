<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_SESSION["user"])) {
        $error = null;
        require_once "database.php";
        $productosPedido = json_decode($_POST["productosPedido"], true);
        $idPedido = uniqid("P");
        $statement = $conne->prepare("INSERT INTO CabeceraPedidos VALUES (:idPedido, :correoUsuario, NOW())");;
        $statement->bindParam(":idPedido", $idPedido);
        $statement->bindParam(":correoUsuario", $_SESSION["user"]);
        try {
            $statement->execute();
        } catch (PDOException $e) {
            $error = "Ha habido un error al guardar el pedido";
        }
        if ($error == null) {
            
        }
    }
?>