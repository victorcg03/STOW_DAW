<?php
require 'config.php';
$raw_data = file_get_contents("php://input");
error_log("Raw data received: " . $raw_data); // Esto registrará los datos en el log de errores de PHP

$data = json_decode(file_get_contents("php://input"));
if (!$data) {
  http_response_code(400);
  echo json_encode(["message" => "Error en los datos enviados", "data" => file_get_contents("php://input"), "post" => $_POST]);
  exit;
}


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
$stmt = $pdo->prepare("INSERT INTO usuarios (Correo, Nombre, Apellidos, Contrasena, Verificado) VALUES (?, ?, ?, ?, True)");
$stmt->execute([$data->email, $data->name, $data->surnames, $hashed_password]);

echo json_encode(["message" => "Usuario registrado correctamente"]);
