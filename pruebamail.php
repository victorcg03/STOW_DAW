<?php
require_once "./sendmail.php";

$cuerpo ='<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codigo verificación</title>
    <style>
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body{
            width: 100vw;
            height: 100vh;
            background-color: rgba(33,37,41, 0.85);
            display: flex;
            padding: 90px;
            align-items: center;
            flex-direction: column;
        }
        h1 {
            font-size: 60px;
            color: #FF0000;
            text-shadow: #000 5px 0px;
            margin-bottom: 30px;
        }
        p{
            font-size:40px;
            font-weight: 800;
        }
    </style>
</head>

<body>
    <h1>STOW</h1>
    <p>Tu código de verificación es:</p>
</body>

</html>';
enviarCorreo("victorjosecorralguillot@gmail.com", "Codigo de verificación", $cuerpo);
