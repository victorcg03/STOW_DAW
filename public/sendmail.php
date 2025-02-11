<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function enviarCorreo($destinatario, $asunto, $mensaje, $imagenes = [])
{
    /*Clase para tratar con excepciones y errores*/
    require_once '../PHPMailer/src/Exception.php';
    /*Clase PHPMailer*/
    require_once '../PHPMailer/src/PHPMailer.php';
    /*Clase SMTP necesaria para la conexión con un servidor SMTP*/
    require_once '../PHPMailer/src/SMTP.php';

    require '../env.php';

    $debug = false;
    try {
        // Crear instancia de la clase PHPMailer
        $mail = new PHPMailer($debug);
        if ($debug) {
            // Genera un registro detallado
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        }
        // Autentificación con SMTP
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        // Login
        $mail->Host = $mailHost;
        $mail->Port = $mailPort;
        $mail->Username = $mailUser;
        $mail->Password = $mailPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->setFrom($mailUser);
        $mail->addAddress($destinatario);

        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;
        foreach ($imagenes as $imagen) {
            $mail->addEmbeddedImage($imagen["src"], $imagen["cid"]);
        }
        if($mail->send()){
            return json_encode(["enviado" => true]);
        } else {
            return json_encode(["enviado" => false, "error" => $mail->ErrorInfo]);
        }
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: " . $e->getMessage();
    }
}
