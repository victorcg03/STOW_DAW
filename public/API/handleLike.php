<?php
require_once 'jwt_middleware.php';
require_once 'config.php';
$decoded = verificarToken();

// Lee los datos JSON del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

$like = null;
$stmt = $pdo->prepare("SELECT ProductoID FROM likes WHERE Usuario = ? AND ProductoID = ?");
$stmt->execute([$decoded->correo, $data['ID']]);
$like = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($like);
