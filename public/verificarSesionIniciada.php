<?php
    session_start();
    if (!empty($_SESSION["user"])) {
        echo json_encode(["loggedIn" => true, "user" => $_SESSION["user"]]);
    } else {
        echo json_encode(["loggedIn" => false]);
    }
?>