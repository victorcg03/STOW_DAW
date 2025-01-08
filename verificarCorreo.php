<?php
    session_start();
    $correoUsuario;
    require_once "./database.php";
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["codigo"])) {
        $correoUsuario = $_POST["correo"];
        $statement = $conne->prepare("SELECT * FROM usuarios WHERE LOWER(Correo) = LOWER(:correo) LIMIT 1");
        $statement->bindParam(":correo", $correoUsuario);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $codigoVerificadoUsuario = $data[0]["Verificado"];
        if ($_POST["codigo"] == $codigoVerificadoUsuario) {
            $statement = $conne->prepare("UPDATE usuarios SET Verificado = 'True' WHERE LOWER(Correo) = LOWER(:correo)");
            $statement->bindParam(":correo", $correoUsuario);
            $statement->execute();
            $_SESSION['user'] = $correoUsuario;
            header("Location: ./");
        } else {
            $error = "El código no es correcto";
        }
    } else {
        $correoUsuario = $_SESSION["user"];
        $_SESSION["user"] = null;
        $statement = $conne->prepare("SELECT * FROM usuarios WHERE LOWER(Correo) = LOWER(:correo) LIMIT 1");
        $statement->bindParam(":correo", $correoUsuario);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $verificado = $data[0]["Verificado"];
        if ($verificado == "True") {
            $_SESSION['user'] = $correoUsuario;
            header("Location: ./");
        } else {
            $codigoVerificacion = random_int(00000, 99999);
            $statement = $conne->prepare("UPDATE usuarios SET Verificado = :codigoVerificacion WHERE LOWER(Correo) = LOWER(:correo)");
            $statement->bindParam(":correo", $correoUsuario);
            $statement->bindParam(":codigoVerificacion", $codigoVerificacion);
            $statement->execute();
            require_once "sendmail.php";
            $mensaje = '<!DOCTYPE html>
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
                <p>Tu código de verificación es: ' . $codigoVerificacion . '</p>
            </body>
            
            </html>';
            enviarCorreo($correoUsuario, "Código de verificación", $mensaje);
        }
    }

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title class="notranslate">STOW</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cade5ed75a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/register.css">
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,es,fr,de,it'
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script src="./js/register.js"></script>

</head>

<body>
    <div id="capa"></div>
    <a class="notranslate stow" href="./">STOW</a>
    <div class="card">
        <form action="./verificarCorreo.php" method="POST">
            <h2>Verificación</h2>
            <input type="text" value="<?= $correoUsuario ?>" name="correo" hidden >
            <div class="form-row">
                <input type="text" name="codigo" id="codigo" required>
                <label for="codigo">Código de verificación:</label>
            </div>
            <div class="boton">
            <button>Enviar</button>
            </div>
        </form>
        <?php 
            if (!empty($error)) { ?>
                <p class="error"><?= $error ?></p>
            <?php }
        ?>
    </div>
    <div id="google_translate_element"></div>
</body>
</html>
