<?php
require_once 'jwt_middleware.php';
require_once 'config.php';
$decoded = verificarToken();

// Lee los datos JSON del cuerpo de la solicitud
try {
  $data = json_decode(file_get_contents("php://input"), true);

  $stmt = $pdo->prepare("SELECT ProductoID FROM likes WHERE Usuario = ? AND ProductoID = ?");
  $stmt->execute([$decoded->correo, $data['ID']]);
  $like = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($stmt->rowCount() === 0) {
    $stmt = $pdo->prepare("INSERT INTO likes (ProductoID, Usuario) VALUES (?, ?)");
    $stmt->execute([$data['ID'], $decoded->correo]);
  } else {
    $stmt = $pdo->prepare("DELETE FROM likes WHERE Usuario = ? AND ProductoID = ?");
    $stmt->execute([$decoded->correo, $data['ID']]);
  }
  echo json_encode(["ok" => true]);
} catch (Exception $e) {
  echo json_encode(array("message" => $e->getMessage(), "like" => $like));
  die();
}
