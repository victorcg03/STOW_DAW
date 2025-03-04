<?php
require_once __DIR__ . '/../../env.php';
$host = "localhost";
$user = $userBBDD;
$password = $bbddPassword;
$db = "stow";
$cadenaConexion = "mysql:host=$host;dbname=$db;";
try {
  $pdo = new PDO($cadenaConexion, $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Error al conectarse a $db: " .  $e->getMessage();
  $pdo = null;
}
