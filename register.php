<?php
session_start();
if (!empty($_SESSION['user'])) {
    header("Location: ./");
    return;
}
if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST["nombre"]) && !empty($_POST["apellidos"]) && !empty($_POST["correo"]) && !empty($_POST["nombre"]) && !empty($_POST["password"])) {
    require_once "./database.php";
    $statement = $conne->prepare("SELECT * FROM usuarios WHERE LOWER(Correo) = LOWER(:correo) LIMIT 1");
    $statement->bindParam(":correo", $_POST["correo"]);
    $statement->execute();
    if ($statement->rowCount() != 0) {
        $error = "El correo introducido ya está en uso";
    } else if (!filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL)) {
        $error = "El correo introducido no es valido";
    } else {
        $statement = $conne->prepare("INSERT INTO usuarios VALUES (:correo, :nombre, :apellidos, :password, 'false')");
        $passwordHash = password_hash($_POST["password"], PASSWORD_BCRYPT);
        $statement->bindParam(":correo", $_POST["correo"]);
        $statement->bindParam(":nombre", $_POST["nombre"]);
        $statement->bindParam(":apellidos", $_POST["apellidos"]);
        $statement->bindParam(":password", $passwordHash);
        try {
            if ($statement->execute()) {
                $_SESSION["user"] = $_POST["correo"];
                header("Location: ./verificarCorreo");
            } else {
                $error = "Ha habido un error al guardar al usuario";
            }
        } catch (PDOException $e) {
            $error = "Ha habido un error al guardar al usuario" . $e;
        }
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
    <script src="./js/register.js"></script>

</head>

<body>
    <div id="capa"></div>
    <a class="notranslate stow" href="./">STOW</a>
    <div class="card">
        <form action="./register.php" method="POST">
            <h2>Registro</h2>
            <div class="form-row">
                <input type="text" name="nombre" id="nombre" required value="<?= !empty($_POST["nombre"]) ? $_POST["nombre"] : "" ?>">
                <label for="nombre">Nombre:</label>
            </div>
            <div class="form-row">
                <input type="text" name="apellidos" id="apellidos" required value="<?= !empty($_POST["apellidos"]) ? $_POST["apellidos"] : "" ?>">
                <label for="apellidos">Apellidos:</label>
            </div>
            <div class="form-row">
                <input type="text" name="correo" id="correo" required value="<?= !empty($_POST["correo"]) ? $_POST["correo"] : "" ?>">
                <label for="correo">Correo:</label>
            </div>
            <div class="form-row">
                <input type="password" name="password" id="password" required value="<?= !empty($_POST["password"]) ? $_POST["password"] : "" ?>">
                <label for="password">Contraseña:</label>
                <i class="fa-solid fa-eye" id="verPass"></i>
            </div>
            <div class="boton">
                <button>Enviar</button>
            </div>
        </form>
        <p class="tieneCuenta">¿Tienes ya una cuenta?<a href="./login.php"> Iniciar sesión</a></p>
        <?php
        if (!empty($error)) { ?>
            <p class="error"><?= $error ?></p>
        <?php }
        ?>
    </div>
    <div id="google_translate_element"></div>
</body>

</html>