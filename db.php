<?php
    $db_user = "root";
    $db_password = "";
    $db_name = "5dvision";
    $db_host = "localhost";

    $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);

    if($mysqli->connect_error) {
        printf("Connect failed: %s", $mysqli->connect_error);
        exit();
    }
?>