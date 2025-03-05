<?php
require_once 'jwt_middleware.php';
require_once 'config.php';
$decoded = verificarToken();

// Lee los datos JSON del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

echo json_encode($data);
