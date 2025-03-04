<?php
require 'config.php';

$data = json_decode(file_get_contents("php://input"));

if (!$data->email || !$data->password || !$data->name || !$data->surnames) {
  http_response_code(400);
  echo json_encode(["message" => "Todos los campos son obligatorios", "data" => $data]);
  exit;
}

// Verificar si el email ya existe
$stmt = $pdo->prepare("SELECT Correo FROM usuarios WHERE Correo = ?");
$stmt->execute([$data->email]);
if ($stmt->fetch()) {
  http_response_code(400);
  echo json_encode(["message" => "El email ya está en uso"]);
  exit;
}

// Hashear la contraseña
$hashed_password = password_hash($data->password, PASSWORD_BCRYPT);

// Insertar el usuario
$stmt = $pdo->prepare("INSERT INTO usuarios (Correo, Nombre, Apellidos, Contrasena, Verificado) VALUES (?, ?, ?, True)");
$stmt->execute([$data->email, $data->name, $data->surnames, $hashed_password]);

echo json_encode(["message" => "Usuario registrado correctamente"]);
