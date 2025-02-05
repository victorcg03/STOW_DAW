<?php
  require_once './comprobarSesionIniciada.php';
  if ($SESION_INICIADA) {
    require_once '../../database.php';
    try {
      $statement = $conne->prepare("SELECT * FROM productos");
      if (!$statement->execute()) {
        echo json_encode(["error" => true, "msg" => "Error al obtener los productos"]);
      } else {
        $productos = $statement->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["error" => false, "productos" => $productos]);
      }
    } catch (PDOException $e) {
      echo json_encode(["error" => true, "msg" => "Error al obtener los productos: " . $e->getMessage()]);
      die();
    }
  } else {
    echo json_encode(["error" => true, "msg" => "No has iniciado sesión"]);
  }
  
?>