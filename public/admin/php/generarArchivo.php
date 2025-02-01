<?php
require_once "./comprobarSesionIniciada.php";
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $SESION_INICIADA && !empty($_GET["pedidos"])) {
    try {
        $directorio = "../pedidos/";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreArchivo = "pedidos-" . date("d-m-Y_H-i-s") . ".txt";
        $rutaArchivo = $directorio . $nombreArchivo;
        $archivo = fopen($rutaArchivo, "w");

        if (!$archivo) {
            echo json_encode(["error" => true, "msg" => "No se pudo crear el archivo"]);
            die();
        }

        $pedidos = json_decode($_GET["pedidos"], true);

        if ($pedidos === null) {
            echo json_encode(["error" => true, "msg" =>  "Error al decodificar JSON"]);
            die();
        }

        fwrite($archivo, "ID Pedido\tCorreo Usuario\tFecha\tSubtotal\tIVA\tEnvio\tTotal\tCompletado\n");
        foreach ($pedidos as $pedido) {
            fwrite($archivo, implode("\t", $pedido["cabecera"]) . "\n");
        }
        fclose($archivo);

        // Devolver la URL del archivo al frontend
        echo json_encode(["msg" => "Archivo generado correctamente", "ruta" => $rutaArchivo, "nombre" => $nombreArchivo]);
        die();
    } catch (Exception $e) {
        echo json_encode(["error" => true, "msg" =>  "Error al generar el archivo: " . $e->getMessage()]);
        die();
    }
} else {
    echo json_encode(["error" => true, "msg" =>  "Acceso no permitido o datos incompletos"]);
    die();
}
