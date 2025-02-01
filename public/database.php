<?php
    require_once __DIR__ . '/../env.php';
    $host = "localhost";
    $user = $userBBDD;
    $password = $bbddPassword;
    $db = "stow";
    $cadenaConexion = "mysql:host=$host;dbname=$db;";
    try {
        $conne = new PDO($cadenaConexion, $user, $password);
    } catch (PDOException $e)  {
        echo "Error al conectarse a $db: ".  $e->getMessage();
        $conne = null;
    }
?>