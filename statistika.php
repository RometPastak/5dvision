<?php
    require('db.php');
    session_start();

    if(!isset($_SESSION['logged_in'])) {
        header('Location: index.php');
    }

    $messages = [];

    $liik_query = "SELECT DISTINCT `liik` FROM linnud";
    $liik_result = $mysqli->query($liik_query);

    if(isset($_POST["otsi"])) {
        $liik = $_POST['liik'];

        if($liik == "") {
            array_push($messages, "Te peate valima ka liigi!");
        }
        else {
            $query = "SELECT `Tiiva pikkus`, `Liik`, `Mass`, `Kuupäev` FROM linnud WHERE `Liik`='$liik'";
            $result = $mysqli->query($query);

            $rongastatud_lindude_arv = [];
            $suurim_aasta = 0;
            $tiivapikkused = [];
            $kaalud = [];

            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $aasta = date("Y", strtotime($row['Kuupäev']));

                if(array_key_exists($aasta, $rongastatud_lindude_arv)) {
                    $rongastatud_lindude_arv[$aasta]++;

                    if($rongastatud_lindude_arv[$aasta] > $suurim_aasta) {
                        $suurim_aasta = $rongastatud_lindude_arv[$aasta];
                    }
                } 
                else {
                    $rongastatud_lindude_arv[$aasta] = 1;
                }

                if($row['Tiiva pikkus'] != '') {
                    if(isset($tiivapikkused[$aasta])) {
                        array_push($tiivapikkused[$aasta], $row['Tiiva pikkus']);
                    }
                    else {
                        $tiivapikkused[$aasta] = array($row['Tiiva pikkus']);
                    }
                }

                if($row['Mass'] != '') {
                    if(isset($kaalud[$aasta])) {
                        array_push($kaalud[$aasta], $row['Mass']);
                    }
                    else {
                        $kaalud[$aasta] = array($row['Mass']);
                    }
                }
            }
        }
    }
?>

<html>
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Statistika</title>
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
        <form class="statistika" action="statistika.php" method="post">
            <?php
                foreach($messages as $message) {
                    printf("%s<br>", $message);
                }
            ?>
            <select name="liik">
                <option value="">Vali liik</option>
                <?php
                    while($row = $liik_result->fetch_array(MYSQLI_ASSOC)) {
                        echo "<option>" . $row['liik'] . "</option>";
                    }
                ?>
            </select>
            <input type="submit" class="btn btn-outline-secondary" name="otsi" value="Otsi" />
        </form>
        <table class="table table-bordered table-dark table-striped">
            <thead>
                <tr>
                    <th>Rõngastatud lindude arv aastate lõikes</th>
                    <th>Minimaalne tiivpikkus</th>
                    <th>Keskmine tiivpikkus</th>
                    <th>Maksimaalne tiivpikkus</th>
                    <th>Minimaalne kaal</th>
                    <th>Keskmine kaal</th>
                    <th>Maksimaalne kaal</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($result)): ?>
                    <?php foreach($rongastatud_lindude_arv as $aasta => $mitu): ?>
                        <tr>
                            <td>
                                <?php
                                    if($mitu == $suurim_aasta) {
                                        echo "<span style='color:red; font-weight:bold;'>" . $aasta . " - " . $mitu . "</span>";
                                    }
                                    else {
                                        echo $aasta . " - " . $mitu;
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if(isset($tiivapikkused[$aasta])) {
                                        echo number_format(floatval(min($tiivapikkused[$aasta])), 2);
                                    }
                                    else {
                                        echo "Selle aasta kohta pole andmeid kuvada!";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if(isset($tiivapikkused[$aasta])) {
                                        echo number_format(floatval(array_sum($tiivapikkused[$aasta]) / count($tiivapikkused[$aasta])), 2);
                                    }
                                    else {
                                        echo "Selle aasta kohta pole andmeid kuvada!";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if(isset($tiivapikkused[$aasta])) {
                                        echo number_format(floatval(max($tiivapikkused[$aasta])), 2);
                                    }
                                    else {
                                        echo "Selle aasta kohta pole andmeid kuvada!";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if(isset($kaalud[$aasta])) {
                                        echo number_format(floatval(min($kaalud[$aasta])), 2);
                                    }
                                    else {
                                        echo "Selle aasta kohta pole andmeid kuvada!";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if(isset($kaalud[$aasta])) {
                                        echo number_format(floatval(array_sum($kaalud[$aasta]) / count($kaalud[$aasta])), 2);
                                    }
                                    else {
                                        echo "Selle aasta kohta pole andmeid kuvada!";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if(isset($kaalud[$aasta])) {
                                        echo number_format(floatval(max($kaalud[$aasta])), 2);
                                    }
                                    else {
                                        echo "Selle aasta kohta pole andmeid kuvada!";
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </body>
</html>