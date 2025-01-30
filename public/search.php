<?php
if ($_SERVER['REQUEST_METHOD'] == "GET" && !empty($_GET['search'])) {
    require_once "./database.php";
    
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

    // Ejecutar la consulta y devolver los resultados
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
?>
