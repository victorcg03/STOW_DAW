<?php
$dir = __DIR__ . "/env.php";
echo json_encode(["Directorio" =>  __DIR__, "Existe?" => file_exists(__DIR__)]);
die();
