<?php
require_once 'config.php';
header("Access-Control-Allow-Origin: *");  // Permite solicitudes de cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");  // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization");  // Cabeceras permitidas

try {
    if (isset($_GET["id"])) {
        $statement = $pdo->prepare("SELECT * FROM productos WHERE id = :id AND activo = 1");
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
