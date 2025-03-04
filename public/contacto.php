<?php
session_start();
require_once './partials/header.php';
require_once './sendmail.php';
$noti = "";
$error = "";
if (!empty($_SESSION["user"]) && $_SERVER["REQUEST_METHOD"] === "POST") {
  $correoAdmin = "victorjosecorralguillot@gmail.com";
  $correo = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $motivoContacto = filter_input(INPUT_POST, 'motivo', FILTER_SANITIZE_STRING);
  $mensaje = filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING);

  if ($correo && $motivoContacto && $mensaje) {
    $plantillaCorreo = "
    <html>
      <head>
        <title>Solicitud de contacto recibida - STOW</title>
      </head>
      <body>
        <h1>Gracias por contactar con nosotros</h1>
        <p>¡Hemos recibido tu correo y en breves contactaremos contigo!</p>
        <p><strong>Motivo de contacto:</strong> $motivoContacto</p>
        <p><strong>Mensaje:</strong> $mensaje</p>
      </body>
    </html>
";

    $mensajeUsu = enviarCorreo($correo, "Gracias por contactar con nosotros", $plantillaCorreo);
    $mensajeAdmin = enviarCorreo($correoAdmin, $motivoContacto, $mensaje);

    if ($mensajeAdmin && $mensajeUsu) {
      $noti = "Correo enviado correctamente";
    } else {
      $error = "Error al enviar el correo";
    }
  } else {
    $error = "Por favor, completa todos los campos correctamente.";
  }
}

$user = $_SESSION['user'] ?? '';
$disabled = !empty($user) ? 'readonly' : '';
?>
<link rel="stylesheet" href="./css/contacto.css">
<main>
  <div class="c"></div>
  <h1>Contacto</h1>
  <p>¡Hola! ¿En qué podemos ayudarte?</p>
  <form action="./contacto" method="post">
    <div class="form-row">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required <?= $disabled ?> value="<?= htmlspecialchars($user) ?>">
    </div>
    <div class="form-row">
      <label for="motivo">Motivo de contacto:</label>
      <input type="text" id="motivo" name="motivo" required placeholder="Producto dañado...">
    </div>
    <div class="form-row mensaje">
      <label for="mensaje">Mensaje:</label>
      <textarea id="mensaje" name="mensaje" required placeholder="El producto..."></textarea>
    </div>
    <button type="submit">Enviar</button>
    <?php if (!empty($noti)): ?>
      <p class="noti"><?= htmlspecialchars($noti) ?></p>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
  </form>
</main>
<?php require_once './partials/footer.php'; ?>