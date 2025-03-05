<?php
require 'vendor/autoload.php';
require 'env.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


function verificarToken()
{
  global $secret_key;
  $headers = apache_request_headers();

  if (!isset($headers["Authorization"])) {
    http_response_code(401);
    echo json_encode(["message" => "No autorizado"]);
    exit;
  }

  $token = str_replace("Bearer ", "", $headers["Authorization"]);

  try {
    $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
    return $decoded;
  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["message" => "Token inválido"]);
    exit;
  }
}
