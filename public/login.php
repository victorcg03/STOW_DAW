<?php
session_start();
if (!empty($_SESSION['user'])) {
    header("Location: ./");
    return;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["correo"]) && !empty($_POST["password"])) {
    require_once "./database.php";
    $statement = $conne->prepare("SELECT * FROM Usuarios WHERE LOWER(Correo) = LOWER(:correo) LIMIT 1");
    $statement->bindParam(":correo", $_POST["correo"]);
    try{
        $statement->execute();
    }catch(PDOException $e){
        echo "Error: " . $e->getMessage();
    }

    if($statement->rowCount() != 0) {
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $passwordHash = $data[0]["Contrasena"];
        if (password_verify($_POST["password"], $passwordHash)) {
            if($data[0]["Verificado"] !== "True") {
                header("Location: ./verificarCorreo?correo=" . $_POST["correo"]);
            } else {
                $_SESSION["user"] = $_POST["correo"];
                header("Location: ./");
            }
        } else {
            $error = "Credenciales erroneas";
        }
    } else {
        $error = "Credenciales erroneas";
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
                pageLanguage: 'es',
                includedLanguages: 'en,es,fr,de,it'
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script src="./js/login.js"></script>
</head>

<body>
    <div id="capa"></div>
    <a class="notranslate stow" href="./">STOW</a>
    <div class="card">
        <form action="./login.php" method="POST">
            <h2>Inicio de Sesión</h2>
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
        <p class="tieneCuenta">¿Todavía no tienes una cuenta?<a href="./register.php"> Registrarse</a></p>
        <?php 
            if (!empty($error)) { ?>
                <p class="error"><?= $error ?></p>
            <?php }
        ?>
    </div>
    <div id="google_translate_element"></div>
</body>

</html>