<?php
    require('db.php');
    session_start();

    $messages = [];

    if(isset($_SESSION['logged_in'])) {
        session_destroy();
        header('Location: index.php');
    }

    if(isset($_POST["login"])) {
        $kasutajanimi = $_POST['username'];
		$parool = $_POST['password'];
        
        if($kasutajanimi == "") {
            array_push($messages, "Te ei sisestanud kasutajanime!");
        }

        if($parool == "") {
            array_push($messages, "Te ei sisestanud parooli!");
        }

        if($kasutajanimi != "" && $parool != "") {
            $query = "SELECT kasutajatunnus, parool FROM kasutajad WHERE kasutajatunnus='$kasutajanimi' AND parool='$parool'";
            $result = $mysqli->query($query);
            
            if(mysqli_num_rows($result) === 1) {
                array_push($messages, "Sisselogimine õnnestus!");
                $_SESSION['logged_in'] = $kasutajanimi;
                header("Refresh: 1; url=index.php");
            }
            else {
                array_push($messages, "Sisestasite vale kasutajanime või parooli!");
            }
        }
    }
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sisselogimine</title>
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
                    <span class="navbar-text" style="margin-left: 10px;">
                        <a class="nav-link" href="register.php">Registreeru</a>
                    </span>
                </div>
            </div>
        </nav>
        <form class="login" action="login.php" method="post">
            <?php
                foreach($messages as $message) {
                    printf("%s<br>", $message);
                }
            ?>
            <input type="text" name="username" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
            <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
            <input type="submit" class="btn btn-outline-secondary" name="login" value="Logi sisse" />
        </form>
    </body>
</html>