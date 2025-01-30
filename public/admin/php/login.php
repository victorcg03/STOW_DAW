<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try{
      verificarDatos();
    }catch(Exception $e){
      echo json_encode(["error" => "No se ha podido iniciar sesión"]);
    }
  } else {
    header("Location: ../../index.php");
  }
  function verificarDatos(){
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];
    $nombreArchivo = "../admin.txt";
    //Controlo que el fichero exista
    if (!file_exists($nombreArchivo)) {
        echo json_encode(["error" => "Error al acceder a los datos"]);
    }else{
      $archivoAbierto = @fopen($nombreArchivo, 'r');
      if (!$archivoAbierto) {
        echo json_encode(["error" => "No se ha podido iniciar sesión"]);
      }else{
        $linea = fgets($archivoAbierto);
        $datosAdmin = explode(";", $linea);
        fclose($archivoAbierto);
      }
      if ($usuario == $datosAdmin[0] && $contrasena == $datosAdmin[1]) {
        session_start();
        $_SESSION["idUsuario"] = $datosAdmin[2];
        echo json_encode(["sesionIniciada" => true]);
      } else {
        echo json_encode(["error" => "Credenciales incorrectas"]);
      }
    }
  }
?>