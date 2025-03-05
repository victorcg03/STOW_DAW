<?php
// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permitir métodos específicos (puedes agregar más si los necesitas)
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Permitir los headers necesarios para autenticación
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejar preflight request (cuando el navegador envía OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit();
}

require 'config.php';
require 'jwt_middleware.php';

$decoded = verificarToken();

$stmt = $pdo->prepare("SELECT Correo, Nombre, Apellidos FROM usuarios WHERE Correo = ?");
$stmt->execute([$decoded->correo]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($user);
