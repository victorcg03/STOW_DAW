<?php
require 'config.php';
require 'jwt_middleware.php';

$decoded = verificarToken();

$stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id = ?");
$stmt->execute([$decoded->user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($user);
