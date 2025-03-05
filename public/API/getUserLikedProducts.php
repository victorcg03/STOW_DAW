<?php
require_once 'jwt_middleware.php';
require_once 'config.php';
$decoded = verificarToken();

// Lee los datos JSON del cuerpo de la solicitud
try {
  $stmt = $pdo->prepare("SELECT ProductoID FROM likes WHERE Usuario = ? AND activo = 1");
  $stmt->execute([$decoded->correo]);
  $likes = $stmt->fetch(PDO::FETCH_ASSOC);

  echo json_encode($likes);
} catch (Exception $e) {
  echo json_encode(array("message" => $e->getMessage()));
  die();
}
