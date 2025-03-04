<?php
$dir = __DIR__;
echo json_encode(["Directorio" =>  __DIR__, "Existe?" => file_exists(__DIR__)]);
die();
