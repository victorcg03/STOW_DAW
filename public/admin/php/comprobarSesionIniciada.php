<?php
session_start();
$nombreArchivo = "../../../admin.txt";

// Verificar que el archivo existe
if (!file_exists($nombreArchivo)) {
    return false;
}

$archivoAbierto = @fopen($nombreArchivo, 'r');
if (!$archivoAbierto) {
    return false;
}

$linea = fgets($archivoAbierto);
$datos = explode(";", $linea);
$idAdmin = $datos[2];
fclose($archivoAbierto);

$SESION_INICIADA = (!empty($_SESSION["idUsuario"]) && $_SESSION["idUsuario"] == $idAdmin);

// Si se accede directamente al script, solo responde con JSON
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
    echo json_encode(["sesionIniciada" => $SESION_INICIADA]);
    die();
}
