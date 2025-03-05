<?php
require_once 'jwt_middleware.php';
require_once 'config.php';
$decoded = verificarToken();
echo json_encode(["message" => "Hola, " . $decoded->correo]);
