<?php
  session_start();
  $nombreArchivo = "../admin.txt";
  //Controlo que el fichero exista
  if (!file_exists($nombreArchivo)) {
      return false;
  }else{
    $archivoAbierto = @fopen($nombreArchivo, 'r');
    if (!$archivoAbierto) {
      return false;
    }else{
      $linea = fgets($archivoAbierto);
      $datos = explode(";", $linea);
      $idAdmin = $datos[2];
      fclose($archivoAbierto);
    }
  }
  $SESION_INICIADA = (!empty($_SESSION["idUsuario"]) && $_SESSION["idUsuario"] == $idAdmin);
  echo json_encode(["sesionIniciada" => $SESION_INICIADA]);
  return $SESION_INICIADA;
?>