<?php
session_start();
if (!empty($_SESSION['user'])) {
    header("Location: ./index.php");
    return;
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
        <form action="./register.php" method="POST">
            <h2>Registro</h2>
            <div class="form-row">
                <input type="text" name="nombre" id="nombre" required>
                <label for="nombre">Nombre:</label>
            </div>
            <div class="form-row">
                <input type="text" name="apellidos" id="apellidos" required>
                <label for="apellidos">Apellidos:</label>
            </div>
            <div class="form-row">
                <input type="text" name="correo" id="correo" required>
                <label for="correo">Correo:</label>
            </div>
            <div class="form-row">
                <input type="password" name="password" id="password" required>
                <label for="password">Contraseña:</label>
                <i class="fa-solid fa-eye" id="verPass"></i>
            </div>
            <div class="boton">
            <button>Enviar</button>
            </div>
        </form>
    </div>
    <div id="google_translate_element"></div>
</body>

</html>