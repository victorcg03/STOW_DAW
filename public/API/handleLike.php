<?php
require_once 'jwt_middleware.php';
require_once 'config.php';
$decoded = verificarToken();

// Lee los datos JSON del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

$like = null;
$stmt = $pdo->prepare("SELECT ProductoID FROM likes WHERE Usuario = ? AND ProductoID = ?");
$stmt->execute([$decoded->correo, $data['ID']]); // Usa $data['ID'] en lugar de $_POST['ID']
$like = $stmt->fetch(PDO::FETCH_ASSOC);

if ($stmt->rowCount() == 0) {
  $stmt = $pdo->prepare("INSERT INTO likes (Usuario, ProductoID) VALUES (?, ?)");
  $stmt->execute([$decoded->correo, $data['ID']]);
  echo json_encode(["ok" => "ok"]);
} else {
  $stmt = $pdo->prepare("DELETE FROM likes WHERE Usuario = ? AND ProductoID = ?");
  $stmt->execute([$decoded->correo, $data['ID']]);
  echo json_encode(["ok" => "okk"]);
}
