<?php
require_once './partials/header.php';
$loggedIn = false;
if (isset($_SESSION['user_id'])) {
  $loggedIn = true;
}

?>
<main>
  <?php
  // Obtener las opiniones de la base de datos
$opiniones = obtenerOpiniones();

// Mostrar las opiniones
foreach ($opiniones as $opinion) {
  echo "<div class='opinion'>";
  echo "<p>{$opinion['texto']}</p>";
  if ($loggedIn) {
    echo "<button class='like-btn' data-opinion-id='{$opinion['id']}' data-action='like'>Like</button>";
    echo "<button class='dislike-btn' data-opinion-id='{$opinion['id']}' data-action='dislike'>Dislike</button>";
  }
  echo "</div>";
}

// Mostrar formulario para dejar una opinión si el usuario ha iniciado sesión
if ($loggedIn) {
  echo "<form action='guardar_opinion.php' method='post'>";
  echo "<textarea name='opinion' placeholder='Deja tu opinión'></textarea>";
  echo "<button type='submit'>Enviar opinión</button>";
  echo "</form>";
}

// Función para obtener las opiniones de la base de datos
function obtenerOpiniones() {
  return [
    ['id' => 1, 'texto' => '¡Me encanta esta tienda!'],
    ['id' => 2, 'texto' => 'Los productos son de muy buena calidad'],
    ['id' => 3, 'texto' => 'El envío fue muy rápido'],
  ];
}
  ?>
</main>
<?php
require_once './partials/footer.php';
?>
