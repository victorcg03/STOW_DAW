<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit();
}

require __DIR__ . '/vendor/autoload.php';
require 'config.php';
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
