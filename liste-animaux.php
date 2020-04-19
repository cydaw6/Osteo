<?php
session_start();
?>

<style>
    html,
    body {
        height: 100%;
    }

    body {
        margin: 0;
        background: linear-gradient(45deg, #49a09d, #5f2c82);
        font-family: sans-serif;
        font-weight: 100;
    }

    .container {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    table {
        width: 800px;
        border-collapse: collapse;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
        padding: 15px;
        background-color: rgba(255, 255, 255, 0.2);
        color: #e4e4e4;
    }

    th {
        text-align: left;
    }

    thead,
    th {
        background-color: #55608f;
    }



    tr:hover {
        color: white;
        background-color: rgba(255, 255, 255, -0.8);
    }

    td:hover {
        color: #5ff59d;

    }

    #divG>div {
        margin: 10px 0px 60px 0px;
    }
</style>
<html>

<body>
    <div class="container" id="divG">
        <?php
        if (!isset($_SESSION['id'])) {
            echo '<h4>Vous n\'avez pas accès à cette page. </h4>';
        } elseif (!isset($_POST['seeAnimals'])) {
            echo '<h4>Consultation non définie.</h4>';
        } else {
        ?>
            <div class="container" style=" color:white;">
                <?php
                include './includes/button-to-top.php';
                include './includes/database.php';
                $a = $_SESSION['id'];
                $b = $_POST['idProp'];
                $animaux = $db->query("SELECT * FROM nom_proprio NATURAL JOIN animal WHERE osteo_id=$a AND idProprietaire=$b");
                ?>
                <?php echo '<b>Liste des animaux du propriétaire </b>';  ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Espèce</th>
                            <th>Race</th>
                            <th>Taille</th>
                            <th>Poids</th>
                            <th>Sexe</th>
                            <th>Castration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($x = $animaux->fetch()) {
                            $s = ($x['sexe'] == "f") ? 'Femelle' : 'Mâle';
                            $c = ($x['castration'] == "o") ? 'oui' : 'non';
                            echo '<tr>
                            <td>' . $x['nomAnimal'] . '</td>
                            <td>' . $x['espece'] . '</td>
                            <td>' . $x['race'] . '</td>
                            <td>' . $x['taille'] . ' cm' . '</td>
                            <td>' . $x['poids'] . ' kg' . '</td>
                            <td>' . $s . '</td>
                            <td>' . $c . '</td> </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
        }
        ?>
    </div>
</body>

</html>