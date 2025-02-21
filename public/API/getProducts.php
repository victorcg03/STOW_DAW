<?php
    require_once '../database.php';
    if ($_GET["id"]) {
        $statement = $conne->prepare("SELECT * FROM productos WHERE id = :id WHERE activo = 1");
        $statement->bindParam(':id', $_GET["id"]);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } else {
        $statement = $conne->prepare("SELECT * FROM productos WHERE activo = 1");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    }
?>