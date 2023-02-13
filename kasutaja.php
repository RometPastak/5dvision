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

    if(!isset($_SESSION['logged_in'])) {
        header('Location: index.php');
    }

    $messages = [];
    $sisse_logitud = $_SESSION['logged_in'];

    $query = "SELECT `eesnimi`, `perenimi`, `sünniaeg`, `e-postiaadress`, `telefon`, `kasutajatunnus`, `parool` FROM kasutajad WHERE `kasutajatunnus`='$sisse_logitud'";
    $result = $mysqli->query($query);

    if(isset($_POST["muuda"])) {
        $eesnimi = $_POST['eesnimi'];
		$perenimi = $_POST['perenimi'];
        $sunniaeg = $_POST['sünniaeg'];
		$epost = $_POST['epost'];
        $telefon = $_POST['telefon'];
		$kasutajatunnus = $_POST['kasutajatunnus'];
        $parool = $_POST['parool'];

        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            if($eesnimi == "") {
                $eesnimi = $row['eesnimi'];
            }

            if($perenimi == "") {
                $perenimi = $row['perenimi'];
            }

            if($sunniaeg == "") {
                $sunniaeg = $row['sünniaeg'];
            }

            if($epost == "") {
                $epost = $row['e-postiaadress'];
            }

            if($telefon == "") {
                $telefon = $row['telefon'];
            }

            if($kasutajatunnus == "") {
                $kasutajatunnus = $row['kasutajatunnus'];
            }

            if($parool == "") {
                $parool = $row['parool'];
            }
        }

        $update = "UPDATE kasutajad SET `eesnimi`='$eesnimi', `perenimi`='$perenimi', `sünniaeg`='$sunniaeg', `e-postiaadress`='$epost',`telefon`='$telefon', `kasutajatunnus`='$kasutajatunnus', `parool`='$parool' WHERE kasutajatunnus='$kasutajatunnus'";
        $mysqli->query($update);
        array_push($messages, "Andmed edukalt muudetud!");
        header("Refresh: 0.7; url=kasutaja.php");
    }
?>

<html>
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kasutaja andmed</title>
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
        <form class="kasutaja-andmed" action="kasutaja.php" method="post">
            <?php
                foreach($messages as $message) {
                    printf("%s<br>", $message);
                }
            ?>
            <?php while($row = $result->fetch_array(MYSQLI_ASSOC)): ?>
                <div>
                    <label for="eesnimi">Eesnimi</label>
                    <input id="eesnimi" type="text" name="eesnimi" class="form-control" placeholder="<?php echo $row['eesnimi'] ?>" aria-describedby="basic-addon1" />
                </div>
                <div>
                    <label for="perenimi">Perenimi</label>
                    <input id="perenimi" type="text" name="perenimi" class="form-control" placeholder="<?php echo $row['perenimi'] ?>" aria-describedby="basic-addon1" />
                </div>
                <div>
                    <label for="sünniaeg">Sünniaeg</label>
                    <input id="sünniaeg" type="text" name="sünniaeg" class="form-control" placeholder="<?php echo $row['sünniaeg'] ?>" aria-describedby="basic-addon1" />
                </div>
                <div>
                    <label for="epost">E-postiaadress</label>
                    <input id="epost" type="text" name="epost" class="form-control" placeholder="<?php echo $row['e-postiaadress'] ?>" aria-describedby="basic-addon1" />
                </div>
                <div>
                    <label for="telefon">Telefon</label>
                    <input id="telefon" type="text" name="telefon" class="form-control" placeholder="<?php echo $row['telefon'] ?>" aria-describedby="basic-addon1" />
                </div>
                <div>
                    <label for="kasutajatunnus">Kasutajatunnus</label>
                    <input id="kasutajatunnus" type="text" name="kasutajatunnus" class="form-control" placeholder="<?php echo $row['kasutajatunnus'] ?>" aria-describedby="basic-addon1" />
                </div>
                <div>
                    <label for="parool">Parool</label>
                    <input id="parool" type="text" name="parool" class="form-control" placeholder="<?php echo $row['parool'] ?>" aria-describedby="basic-addon1" />
                </div>
                <input type="submit" class="btn btn-outline-secondary" name="muuda" value="Muuda andmeid" />
            <?php endwhile; ?>
        </form>
    </body>
</html>