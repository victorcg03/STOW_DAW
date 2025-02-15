<?php
require_once './partials/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['opinion']) && !empty($_SESSION['user'])) {
  try {
    $stmt = $conne->prepare("INSERT INTO opiniones (email, opinion) VALUES (:email, :opinion)");
    $stmt->bindParam(':email', $_SESSION['user']);
    $stmt->bindParam(':opinion', $_POST['opinion']);
    if ($stmt->execute()) {
      $_SESSION['msg'] = "Opinión enviada correctamente"; // Guardar mensaje en sesión
    } else {
      $_SESSION['msg'] = "Error al enviar la opinión";
    }
  } catch (PDOException $e) {
    $_SESSION['msg'] = "Error al enviar la opinión";
  }

  // Redirigir para evitar reenvío del formulario
  header("Location: opiniones");
  exit();
}
?>

<link rel="stylesheet" href="./css/opiniones.css">
<main>
  <div class="filtro"></div>
  <div class="opiniones">
    <h1>Opiniones de nuestros clientes:</h1>
    <?php
    $stmt = $conne->prepare("SELECT * FROM opiniones");
    $stmt->execute();
    $opiniones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($opiniones as $opinion) { ?>
      <div class="opinion">
        <div class="opinion-header">
          <h2><?= htmlspecialchars($opinion['email']) ?></h2>
          <p><?= htmlspecialchars($opinion['fecha']) ?></p>
        </div>
        <p><?= htmlspecialchars($opinion['opinion']) ?></p>
      </div>
    <?php } ?>
  </div>

  <?php if (!empty($_SESSION['user'])) { ?>
    <form action="./opiniones.php" method="POST">
      <h2>¡Comparte tu opinión!</h2>
      <div class="form-row">
        <label for="name">Correo electrónico:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($_SESSION['user']) ?>" readonly>
      </div>
      <div class="form-row">
        <label for="opinion">Opinión:</label>
        <textarea name="opinion" id="opinion" rows="5" required></textarea>
      </div>
      <div class="form-row">
        <button type="submit">Enviar</button>
      </div>
      <?php
      if (!empty($_SESSION['msg'])) {
        echo "<p class='msg'>{$_SESSION['msg']}</p>";
        unset($_SESSION['msg']); // Borrar mensaje después de mostrarlo
      }
      ?>
    </form>
  <?php } ?>
</main>

<?php require_once './partials/footer.php'; ?>