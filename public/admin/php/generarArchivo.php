<?php
require_once "./comprobarSesionIniciada.php";
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $SESION_INICIADA && !empty($_GET["pedidos"])) {
  try {
    $directorio = "../pedidos/";
    $nombreArchivo = $directorio . "pedidos-" . date("d-m-Y_H-i-s") . ".txt";
    $archivo = fopen($nombreArchivo, "w");
    if (!$archivo) {
      echo json_encode(["error" => true, "msg" => "No se pudo abrir el archivo"]);
      die();
    }
    $pedidos = json_decode($_GET["pedidos"], true);
    fwrite($archivo, "ID Pedido\tCorreo\tusuario\tFecha\tSubtotal\tIVA\tEnvio\tTotal\tCompletado\n");
    foreach ($pedidos as $pedido) {
      fwrite($archivo, implode("\t", $pedido["cabecera"]) . "\n");
    }
    fclose($archivo);
  } catch (Exception $e) {
    echo json_encode(["error" => true, "msg" => "Error al generar el archivo"]);
    die();
  }
  echo json_encode(["msg" => "Archivo generado correctamente", "ruta" => $directorio.$nombreArchivo, "nombre" => $nombreArchivo]);
  die();
}
