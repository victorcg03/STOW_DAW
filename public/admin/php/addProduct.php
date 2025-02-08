<?php
  require_once './comprobarSesionIniciada.php';
  if ($SESION_INICIADA && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../database.php';
    try {
      $statement = $conne->prepare("INSERT INTO productos (ID, Nombre, Sexo, Color, ClaseProducto, Stock, Precio, Imagenes) VALUES (:id, :nombre, :sexo, :color, :clase, :stock, :precio, :imagenes)");
      $id = substr(uniqid("P"), 0, 10);
      $nombre = trim($_POST['nombre']);
      $sexo = trim($_POST['sexo']);
      $color = trim($_POST['color']);
      $clase = trim($_POST['clase']);

      $xs = isset($_POST['xs']) ? (int)$_POST['xs'] : 0;
      $s = isset($_POST['s']) ? (int)$_POST['s'] : 0;
      $m = isset($_POST['m']) ? (int)$_POST['m'] : 0;
      $l = isset($_POST['l']) ? (int)$_POST['l'] : 0;
      $xl = isset($_POST['xl']) ? (int)$_POST['xl'] : 0;
      $stock = "XS:" . $xs . ",S:" . $s . ",M:" . $m . ",L:" . $l . ",XL:" . $xl;

      $statement->bindParam(':id', $id);
      $statement->bindParam(':stock', $stock);
      $statement->bindParam(':precio', $_POST['precio']);
      $statement->bindParam(':nombre', $nombre);
      $statement->bindParam(':sexo', $sexo);
      $statement->bindParam(':color', $color);
      $statement->bindParam(':clase', $clase);
      $imagenes = procesarImagenes($_FILES['imagenes'], $id);
      $statement->bindParam(':imagenes', $imagenes);
      if (!$statement->execute()) {
        echo json_encode(["error" => "Error al agregar el producto"]);
        die();
      } else {
        $st = $conne->prepare("SELECT * FROM productos WHERE ID = :id");
        $st->bindParam(':id', $id);
        $st->execute();
        $producto = $st->fetch(PDO::FETCH_ASSOC);
        echo json_encode(["success" => "Producto agregado correctamente", "producto" => $producto]);
        die();
      }
    } catch (Exception $e) {
      echo json_encode(["error" => "Error al agregar el producto: " . $e->getMessage()]);
      die();
    }
  }
  function procesarImagenes($imagenes, $id){
    $imagenesNuevas = [];
    $ruta = '../../img/';
  
    if (!is_dir($ruta)) {
      mkdir($ruta, 0775, true);
    }
  
    foreach ($imagenes['name'] as $key => $imagen) {
      $tipoArchivo = strtolower(pathinfo($imagen, PATHINFO_EXTENSION));
      $tamanoArchivo = $imagenes['size'][$key];
  
      $formatosPermitidos = ['jpg', 'jpeg', 'png', 'webp'];
      if (!in_array($tipoArchivo, $formatosPermitidos)) {
        echo json_encode(["error" => "Formato no permitido para $imagen"]);
        die();
      }
  
      if ($tamanoArchivo > 2 * 1024 * 1024) {
        echo json_encode(["error" => "$imagen es demasiado grande"]);
        die();
      }
  
      $nombreUnico = $id . "_" . uniqid() . "_$imagen";
      $rutaImagen = $ruta . $nombreUnico;
  
      if (move_uploaded_file($imagenes['tmp_name'][$key], $rutaImagen)) {
        $imagenesNuevas[] = $nombreUnico;
      } else {
        echo json_encode(["error" => "Error al subir $imagen"]);
        die();
      }
    }
  
    return implode(",", $imagenesNuevas);
  }
?>