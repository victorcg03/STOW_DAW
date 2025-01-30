<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
} else {
    require_once "./database.php";
    $stmt = $conne->prepare("SELECT * FROM likes WHERE ProductoID = :idProducto AND Usuario = :usuario");
    $stmt->bindParam(":idProducto", $data["idProducto"]);
    $stmt->bindParam(":usuario", $data["usuario"]);
    try {
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 0) {
                $stmt = $conne->prepare("INSERT INTO likes VALUES (:idProducto, :usuario)");
            } else {
                $stmt = $conne->prepare("DELETE FROM likes WHERE ProductoID = :idProducto AND Usuario = :usuario");
            }
            $stmt->bindParam(":idProducto", $data["idProducto"]);
            $stmt->bindParam(":usuario", $data["usuario"]);
            try {
                if ($stmt->execute()) {
                    echo json_encode(["success" => true, "message" => "Datos guardados"]);
                }
            } catch (PDOException $e) {
                echo json_encode(["success" => false, "message" => "Error al modificar BBDD" . $e->getMessage()]);
            }
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al obtener datos de la BBDD" . $e->getMessage()]);
    }
}
