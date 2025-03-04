<?php
require 'config.php';
require 'vendor/autoload.php';  // Para JWT
require 'env.php';

use Firebase\JWT\JWT;


$data = json_decode(file_get_contents("php://input"));

if (!$data->email || !$data->password) {
  http_response_code(400);
  echo json_encode(["message" => "Email y contraseña son obligatorios"]);
  exit;
}

// Buscar usuario por email
$stmt = $pdo->prepare("SELECT Correo, Contrasena FROM usuarios WHERE Correo = ?");
$stmt->execute([$data->email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($data->password, $user["Contrasena"])) {
  http_response_code(401);
  echo json_encode(["message" => "Credenciales incorrectas"]);
  exit;
}

// Generar token JWT
$payload = [
  "correo" => $user["Correo"],
  "exp" => time() + (60 * 60 * 24) // Expira en 24 horas
];

$jwt = JWT::encode($payload, $secret_key, 'HS256');

echo json_encode(["token" => $jwt]);
