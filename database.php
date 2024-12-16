<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "stow";
    $cadenaConexion = "mysql:host=$host;dbname=$db;";
    try {
        $conne = new PDO($cadenaConexion, $user, $password);
    } catch (PDOException $e)  {
        echo "Error al conectarse a $bdd: ".  $e->getMessage();
        $conne = null;
    }
?>