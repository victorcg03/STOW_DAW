<?php
if ($_SERVER['REQUEST_METHOD'] == "GET" && !empty($_GET['search'])) {
    if (!isset($conne)) {
        require_once "./database.php";
    }
    $search = $_GET['search'];
    // Dividir la búsqueda en palabras individuales
    $palabras = explode(" ", $search);

    // Generar la condición SQL con marcadores únicos
    $sqlParts = [];
    foreach ($palabras as $index => $palabra) {
        $sqlParts[] = "(Nombre LIKE :palabra$index OR Color LIKE :palabra$index OR ClaseProducto LIKE :palabra$index OR sexo LIKE :palabra$index)";
    }
    // Unir todas las condiciones con AND
    $sqlCondition = implode(" AND ", $sqlParts);
    // Preparar la consulta SQL
    $query = "SELECT * FROM productos WHERE $sqlCondition";
    $stmt = $conne->prepare($query);
    // Vincular cada palabra de búsqueda a su marcador único
    foreach ($palabras as $index => $palabra) {
        $stmt->bindValue(":palabra$index", "%$palabra%", PDO::PARAM_STR);
    }
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        echo json_encode($productos);
        die();
    }
}
?>