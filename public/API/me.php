<?php

require 'config.php';
require 'jwt_middleware.php';

$decoded = verificarToken();

$stmt = $pdo->prepare("SELECT Correo, Nombre, Apellidos FROM usuarios WHERE Correo = ?");
$stmt->execute([$decoded->correo]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($user);
