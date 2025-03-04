<?php
require_once './comprobarSesionIniciada.php';
if ($SESION_INICIADA && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $imagenesNuevas = [];
  $id = trim($_POST['id']);
  if (!empty($_FILES['imagenes']['name'][0])) {
    $imagenesNuevas = procesarImagenes($_FILES['imagenes'], $id);
  }
  require_once '../../database.php';
  try {
    $statement = $conne->prepare("UPDATE productos SET Nombre = :nombre, Sexo = :sexo, Color = :color, ClaseProducto = :clase, Stock = :stock, Precio = :precio, Imagenes = :imagenes WHERE id = :id");
    $nombre = trim($_POST['nombre']);
    $sexo = trim($_POST['sexo']);
    $color = trim($_POST['color']);
    $clase = trim($_POST['clase']);

    $xs = isset($_POST['XS']) ? (int)$_POST['XS'] : 0;
    $s = isset($_POST['S']) ? (int)$_POST['S'] : 0;
    $m = isset($_POST['M']) ? (int)$_POST['M'] : 0;
    $l = isset($_POST['L']) ? (int)$_POST['L'] : 0;
    $xl = isset($_POST['XL']) ? (int)$_POST['XL'] : 0;
    $stock = "XS:" . $xs . ",S:" . $s . ",M:" . $m . ",L:" . $l . ",XL:" . $xl;

    $imagenesAnteriores = isset($_POST['imgs']) ? trim($_POST['imgs']) : "";
    $imagenes = array_filter(array_merge(explode(",", $imagenesAnteriores), $imagenesNuevas));
    $imagenes = implode(",", $imagenes);

    $statement->bindParam(':stock', $stock);
    $statement->bindParam(':precio', $_POST['precio']);
    $statement->bindParam(':nombre', $nombre);
    $statement->bindParam(':sexo', $sexo);
    $statement->bindParam(':color', $color);
    $statement->bindParam(':clase', $clase);
    $statement->bindParam(':id', $id);
    $statement->bindParam(':imagenes', $imagenes);
    if (!$statement->execute()) {
      echo json_encode(["error" => "Error al editar el producto"]);
      die();
    } else {
      echo json_encode(["success" => "Producto editado correctamente", "nuevasImagenes" => $imagenesNuevas]);
      die();
    }
  } catch (PDOException $e) {
    echo json_encode(["error" => "Error al editar el producto: " . $e->getMessage()]);
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

  return $imagenesNuevas;
}
