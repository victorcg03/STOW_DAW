<?php
require_once '../database.php';

// Habilitar CORS correctamente
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

try {
    if (isset($_GET["id"])) {
        $statement = $conne->prepare("SELECT * FROM productos WHERE id = :id AND activo = 1");
        $statement->bindParam(':id', $_GET["id"], PDO::PARAM_INT);
    } else {
        $statement = $conne->prepare("SELECT * FROM productos WHERE activo = 1");
    }

    $statement->execute();
    $result = isset($_GET["id"]) ? $statement->fetch(PDO::FETCH_ASSOC) : $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
