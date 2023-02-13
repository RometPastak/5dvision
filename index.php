<?php
    session_start();

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

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Avaleht</title>
        <link rel="stylesheet" href="style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>
    <body>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <nav class="navbar bg-dark navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="index.php">Avaleht</a>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="otsing.php">Otsing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="statistika.php">Statistika</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="kasutaja.php">Kasutaja andmed</a>
                        </li>
                    </ul>
                    <span class="navbar-text">
                        <a class="nav-link" href="login.php">
                            <?php
                                if(isset($_SESSION['logged_in'])) {
                                    echo "Logi välja";
                                }
                                else {
                                    echo "Logi sisse";
                                }
                            ?>
                        </a>
                    </span>
                    <?php
                        if(!isset($_SESSION['logged_in'])) {
                            echo '<span class="navbar-text" style="margin-left: 10px;">
                                <a class="nav-link" href="register.php">Registreeru</a>
                                </span>';
                        }
                    ?>
                </div>
            </div>
        </nav>
        <div style="text-align: center; margin-top: 1em;">
            <?php
                if(isset($_SESSION['logged_in'])) {
                    echo "Tere tulemast, " . $_SESSION['logged_in'] . "!";
                }
                else {
                    echo "Tere tulemast, külaline!<br />";
                    echo "Et kasutada meie lehte, peate te sisse logima!";
                }
            ?>
        </div>
    </body>
</html>