<?php
      require_once './comprobarSesionIniciada.php';
      if ($SESION_INICIADA && $_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once '../../database.php';
        $id = $_POST['id'];
        try{
          $statement = $conne->prepare("UPDATE productos SET activo = 0 WHERE id = :id");
          $statement->bindParam(':id', $id);
          if (!$statement->execute()) {
            echo json_encode(["error" => "Error al eliminar el producto"]);
          } else {
            echo json_encode(["success" => "Producto eliminado correctamente"]);
          }
        } catch (PDOException $e) {
          echo json_encode(["error" => "Error al eliminar el producto: " . $e->getMessage()]);
          die();
        }
      }
?>