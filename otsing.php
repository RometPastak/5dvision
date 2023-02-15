<?php
    require('db.php');
    session_start();

    if(!isset($_SESSION['logged_in'])) {
        header('Location: index.php');
    }

    $messages = [];

    $tahed_query = "SELECT DISTINCT `Rõngakood:tähed` FROM linnud";
    $tahed_result = $mysqli->query($tahed_query);

    if(isset($_POST["otsi"])) {
        $rongakooditahed = $_POST['rongakooditahed'];
        $rongakoodinumbrid = $_POST['rongakoodinumbrid'];

        if($rongakooditahed == "") {
            array_push($messages, "Te peate sisestama ka rõngakoodi tähed!");
        }

        if($rongakoodinumbrid == "") {
            array_push($messages, "Te peate sisestama ka rõngakoodi numbri!");
        }

        if($rongakooditahed != "" && $rongakoodinumbrid != "") {
            $query = "SELECT * FROM linnud WHERE `Rõngakood:tähed`='$rongakooditahed' AND `Rõngakood:numbrid` LIKE '%$rongakoodinumbrid%'";
            $result = $mysqli->query($query);
        }
    }
?>

<html>
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Otsing</title>
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
        <form class="otsing" action="otsing.php" method="post">
            <?php
                foreach($messages as $message) {
                    printf("%s<br>", $message);
                }
            ?>
            <select name="rongakooditahed" >
                <option selected value="">Vali rõngakoodi tähed</option>
                <?php
                    while($row = $tahed_result->fetch_array(MYSQLI_ASSOC)) {
                        echo "<option>" . $row['Rõngakood:tähed'] . "</option>";
                    }
                ?>
            </select>
            <input type="text" name="rongakoodinumbrid" />
            <input type="submit" class="btn btn-outline-secondary" name="otsi" value="Otsi" />
        </form>
        <table class="table table-bordered table-dark table-striped">
            <thead>
                <tr>
                    <th width="30%">Eesnimi</th>
                    <th width="30%">Perenimi</th>
                    <th width="20%">Rõngakood:tähed</th>
                    <th width="20%">Rõngakood:numbrid</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($result)): ?>
                    <?php if(mysqli_num_rows($result) === 0): ?>
                        <tr>
                            <td colspan="100%" style="text-align: center;">Ühtegi tulemust ei leitud!</td>
                        </tr>
                    <?php endif; ?>
                    <?php while($row = $result->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="row">
                            <td><?php echo $row['Eesnimi']; ?></td>
                            <td><?php echo $row['Perek.nimi']; ?></td>
                            <td><?php echo $row['Rõngakood:tähed']; ?></td>
                            <td><?php echo $row['Rõngakood:numbrid']; ?></td>
                        </tr>
                        <tr class="hidden">
                            <td>
                                Liik - <?php echo $row['Liik']; ?><br />
                                Sugu - <?php echo $row['Sugu']; ?><br />
                                Vanus - <?php echo $row['Vanus']; ?><br />
                                Asukoht - <?php echo $row['Asukoht']; ?><br />
                                Kuupäev - <?php echo $row['Kuupäev']; ?><br />
                                Kellaaeg - <?php echo $row['Kellaaeg']; ?><br />
                                Muud märgised - <?php echo $row['Muud märgised']; ?><br />
                                Metallrõnga info - <?php echo $row['Metallrõnga info']; ?><br />
                                Värvirõnga kood - <?php echo $row['Värvirõnga kood']; ?><br />
                                Pesakonna suurus - <?php echo $row['Pesakonna suurus']; ?><br />
                            </td>
                            <td>
                                Poja vanus - <?php echo $row['Poja vanus']; ?><br />
                                Poja vanuse täpsus - <?php echo $row['Poja vanuse täpsus']; ?><br />
                                Püügimeetod - <?php echo $row['Püügimeetod']; ?><br />
                                Meelitusvahend - <?php echo $row['Meelitusvahend']; ?><br />
                                Kasti/võrgu/pesa nr - <?php echo $row['Kasti/võrgu/pesa nr']; ?><br />
                                Staatus - <?php echo $row['Staatus']; ?><br />
                                Tiiva pikkus - <?php echo $row['Tiiva pikkus']; ?><br />
                                Mass - <?php echo $row['Mass']; ?><br />
                                Rasvasus - <?php echo $row['Rasvasus']; ?><br />
                                Rasvasusskaala - <?php echo $row['Rasvasusskaala']; ?><br />
                            </td>
                            <td>
                                Jooksme pikkus - <?php echo $row['Jooksme pikkus']; ?><br />
                                Jooksme pikkuse meetod - <?php echo $row['Jooksme pikkuse meetod']; ?><br />
                                Noka pikkus - <?php echo $row['Noka pikkus']; ?><br />
                                Noka pikkuse meetod - <?php echo $row['Noka pikkuse meetod']; ?><br />
                                Pea üldpikkus - <?php echo $row['Pea üldpikkus']; ?><br />
                                Tagaküünise pikkus - <?php echo $row['Tagaküünise pikkus']; ?><br />
                                Sulestiku kood - <?php echo $row['Sulestiku kood']; ?><br />
                                Sulgimine - <?php echo $row['Sulgimine']; ?><br />
                                Laba-hoosulgede sulgimine  - <?php echo $row['Laba-hoosulgede sulgimine']; ?><br />
                                3. laba-hoosule pikkus - <?php echo $row['3. laba-hoosule pikkus']; ?><br />
                                Laba-hoosule tipu seisund - <?php echo $row['Laba-hoosule tipu seisund']; ?><br />
                            </td>
                            <td>
                                Saba pikkus - <?php echo $row['Saba pikkus']; ?><br />
                                Sabasulgede vahe - <?php echo $row['Sabasulgede vahe']; ?><br />
                                Karpaalhoosulg - <?php echo $row['Karpaalhoosulg']; ?><br />
                                Vanad kattesuled - <?php echo $row['Vanad kattesuled']; ?><br />
                                Haudelaik - <?php echo $row['Haudelaik']; ?><br />
                                Nukktiib  - <?php echo $row['Nukktiib']; ?><br />
                                Rinnalihas - <?php echo $row['Rinnalihas']; ?><br />
                                Soomääramismeetod - <?php echo $row['Soomääramismeetod']; ?><br />
                                Biotoop - <?php echo $row['Biotoop']; ?><br />
                                Märkused - <?php echo $row['Märkused']; ?><br />
                                Korduspüügid - <?php echo $row['Korduspüügid']; ?><br />
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <script type="text/javascript">
            const rows = document.querySelectorAll("tr#row");
            console.log(rows);

            rows.forEach((row) => {
                row.addEventListener('click', (event) => {
                    const nextRow = event.target.closest('tr').nextElementSibling;

                    if(nextRow.classList.contains('hidden')) {
                        nextRow.classList.remove('hidden');
                    }
                    else {
                        nextRow.classList.add('hidden');
                    }
                });
            });
        </script>
    </body>
</html>