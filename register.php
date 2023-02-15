<?php
    require('db.php');
    session_start();

    if(isset($_SESSION['logged_in'])) {
        header('Location: index.php');
    }

    $messages = [];
    $kasutajatunnus = '';
    $eesnimi = '';
    $perenimi = '';
    $sunniaeg = '';
    $epost = '';
    $telefon = '';

    if(isset($_POST["registreeru"])) {
        $kasutajatunnus = $_POST['kasutajatunnus'];
		$parool = $_POST['parool'];
        $eesnimi = $_POST['eesnimi'];
        $perenimi = $_POST['perenimi'];
        $sunniaeg = $_POST['sünniaeg'];
        $epost = $_POST['epost'];
        $telefon = $_POST['telefon'];

        if(!preg_match("/^[a-zA-Z-' ]*$/", $eesnimi)) {
            $eesnimi = "";
            array_push($messages, "Eesnimi võib sisaldada ainult tähti!");
        }

        if(!preg_match("/^[a-zA-Z-' ]*$/", $perenimi)) {
            $perenimi = "";
            array_push($messages, "Perenimi võib sisaldada ainult tähti!");
        }

        if(!filter_var($epost, FILTER_VALIDATE_EMAIL)) {
            $epost = "";
            array_push($messages, "E-posti aadress ei ole korrektne!");
        }

        if(!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*()\-_=+{};:,<.>ยง~`|\/?])/i', $parool)) {
            array_push($messages, "Parool peab sisaldama vähemalt ühte väikest ja ühte suurt tähte, ühte numbrit ja sümbolit!");
        }

        if(strlen($parool) < 7) {
            array_push($messages, "Parool peab olema vähemalt 7 tähemärki pikk!");
        }

        if(strlen($kasutajatunnus) < 6) {
            $kasutajatunnus = "";
            array_push($messages, "Kasutajatunnus peab olema vähemalt 6 tähemärki pikk!");
        }

        if(preg_match("/^[a-zA-Z-' ]*$/", $eesnimi) && preg_match("/^[a-zA-Z-' ]*$/", $perenimi) && filter_var($epost, FILTER_VALIDATE_EMAIL) && preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*()\-_=+{};:,<.>ยง~`|\/?])/i', $parool) &&
        strlen($parool) >= 7 && strlen($kasutajatunnus) >= 6) {
            $query = "SELECT kasutajatunnus FROM kasutajad WHERE kasutajatunnus='$kasutajatunnus'";
            $result = $mysqli->query($query);

            if(mysqli_num_rows($result) >= 1) {
                $kasutajatunnus = "";
                array_push($messages, "Sellise kasutajanimega kasutaja on juba olemas!");
            }
            else {
                $insert_user = "INSERT INTO kasutajad VALUES ('$eesnimi', '$perenimi', '$sunniaeg', '$epost', '$telefon', '$kasutajatunnus', '$parool')";

                if($mysqli->query($insert_user) === TRUE) {
                    array_push($messages, "Registreerumine õnnestus!");
                    header("Refresh: 1; url=login.php");
                }
            }
        }
    }
?>

<html>
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registreerumine</title>
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
        <form class="register" action="register.php" method="post">
            <?php
                foreach($messages as $message) {
                    printf("%s<br>", $message);
                }
            ?>
            <div>
                <label for="eesnimi">Eesnimi</label>
                <input id="eesnimi" type="text" name="eesnimi" class="form-control" placeholder="Eesnimi" value="<?php echo $eesnimi; ?>" aria-describedby="basic-addon1" />
            </div>
            <div>
                <label for="perenimi">Perenimi</label>
                <input id="perenimi" type="text" name="perenimi" class="form-control" placeholder="Perenimi" value="<?php echo $perenimi; ?>" aria-describedby="basic-addon1" />
            </div>
            <div>
                <label for="sünniaeg">Sünniaeg</label>
                <input id="sünniaeg" type="date" name="sünniaeg" class="form-control" placeholder="Sünniaeg" value="<?php echo $sunniaeg; ?>" aria-describedby="basic-addon1" />
            </div>
            <div>
                <label for="epost">E-postiaadress</label>
                <input id="epost" type="text" name="epost" class="form-control" placeholder="E-postiaadress" value="<?php echo $epost; ?>" aria-describedby="basic-addon1" />
            </div>
            <div>
                <label for="telefon">Telefon</label>
                <input id="telefon" type="text" name="telefon" class="form-control" placeholder="Telefon" value="<?php echo $telefon; ?>" aria-describedby="basic-addon1" />
            </div>
            <div>
                <label for="kasutajatunnus">Kasutajatunnus</label>
                <input id="kasutajatunnus" type="text" name="kasutajatunnus" class="form-control" placeholder="Kasutajatunnus" value="<?php echo $kasutajatunnus; ?>" aria-describedby="basic-addon1" />
            </div>
            <div>
                <label for="parool">Parool</label>
                <input id="parool" type="password" name="parool" class="form-control" placeholder="Parool" aria-describedby="basic-addon1" />
            </div>
            <input type="submit" class="btn btn-outline-secondary" name="registreeru" value="Registreeru" />
        </form>
    </body>
</html>