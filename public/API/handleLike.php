<?php
require_once 'jwt_middleware.php';
require_once 'config.php';
$decoded = verificarToken();
$like = null;
$stmt = $pdo->prepare("SELECT ProductoID FROM likes WHERE Usuario = ? AND ProductoID = ?");
$stmt->execute([$decoded->correo, $_POST['ID']]);
$like = $stmt->fetch(PDO::FETCH_ASSOC);
if ($stmt->rowCount() == 0) {
  $stmt = $pdo->prepare("INSERT INTO likes (Usuario, ProductoID) VALUES (?, ?)");
  $stmt->execute([$decoded->correo, $_POST['ID']]);
  echo json_encode(["ok" => "ok"]);
} else {
  $stmt = $pdo->prepare("DELETE FROM likes WHERE Usuario = ? AND ProductoID = ?");
  $stmt->execute([$decoded->correo, $_POST['ID']]);
  echo json_encode(["ok" => "okk"]);
}
