<?php
session_start(); // On démarre la session AVANT toute chose
?>

<!DOCTYPE html>

<html lang="fr">

<head>
    <title>Osteo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <script src="//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"></script>


    <style type="text/css">
        @media all and (max-width: 600px) {
            .linktitle {
                position: absolute;
                z-index: -1;
                left: -65px !important;
                padding: 28px 14px !important;
                line-height: 0px;
                background: #222527;
            }
        }

        form>input {
            margin: 2px 0px 2px 0px;
        }

        #showContacts {
            width: 100%;
        }

        #describeAnimal,
        th,
        td {
            border: 1px solid #262626;
            border-collapse: collapse;
        }

        #describeAnimal,
        th,
        td {
            padding: 5px;
            text-align: left;
        }

        #describeAnimal tr:nth-child(even) {
            background-color: #262626;
        }

        #describeAnimal tr:nth-child(odd) {
            background-color: #fff;
        }

        #describeAnimal th {
            background-color: #262626;
            color: white;
        }
    </style>
</head>

<body>

    <?php
    if (!isset($_SESSION['id'])) {
        echo '<h4>Vous n\'avez pas accès à cette page. Redirection...</h4>';
        echo '<meta http-equiv="refresh" content="1; URL=./index.php" />';
    } else {
    ?>
        <div style="height: 158px;max-width: 100%;position: relative;z-index: 3;">

            <?php
            include './includes/button-to-top.php';
            include './includes/header.php';
            include './includes/right-navbar.php';
            include './includes/database.php'; // Connexion à la bdd
            $a = $_SESSION['id'];
            $allMedic = $db->query("SELECT idAnimal, nomAnimal, espece, nom, prenom FROM animal an NATURAL JOIN nom_proprio WHERE an.osteo_id=$a");

            function containsSpecialChars($str)
            {
                if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬]/', $str)) {
                    return true;
                }
                return false;
            }

            function containsNumber($str)
            {
                if (preg_match('#[0-9]#', $str)) {
                    return true;
                }
                return false;
            }

            ?>
        </div>
        <br /><br />

        <div style="padding-left: 170px; margin-right: 0px; margin-left: 0px; background-color:white; max-width: 100%;">

            <div class="container" style="background-color: white; max-width: 1060px; min-width: 100px!important;">

                <?php
                $a = $_SESSION['id'];
                $allMedic = $db->query("SELECT * FROM medicament WHERE osteo_id=$a");
                echo '<br>';
                ?>
                <br>


                <div style="position: relative; padding : 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
                    <center>
                        <br>
                        Ajouter un médicament
                        <br>
                        <br>
                        <form method="post" action="?">
                            <br>
                            <input type="text" name="nomMedic" placeholder="Nom" required><br><br>
                            <textarea type="text" name="conditionnement" placeholder="Conditionnement" rows="3" cols="80"></textarea><br>
                            <textarea type="text" name="dilution" placeholder="Dilution" rows="3" cols="80"></textarea><br>
                            <input type="submit" name="subMedic" value="Ajouter">
                            <input type="reset" value="Effacer">
                            <br>
                            <br>
                        </form>
                        <?php
                        if (isset($_POST['subMedic'])) {
                            $b = "-";
                            $c = "-";

                            if (isset($_POST['conditionnement'])) {
                                $b = $_POST['conditionnement'];
                            }

                            if (isset($_POST['dilution'])) {
                                $c = $_POST['dilution'];
                            }


                            $doubleAnimal = $db->prepare("SELECT * FROM medicament WHERE nomMedicament=:nom AND osteo_id= :osteoId");
                            $doubleAnimal->execute(['nom' => $_POST['nomMedic'], 'osteoId' => $_SESSION['id']]);

                            if ($doubleAnimal->rowCount() >= 1) { # verification dans la base unique de l'osteo
                                echo 'Vous avez déjà enregistré ce médicament';
                            } else {
                                $createAnimal = $db->prepare("INSERT INTO medicament VALUES(DEFAULT, :a, :b, :c, :d)");
                                $createAnimal->execute([
                                    'a' => $_POST['nomMedic'], 'b' => $_POST['conditionnement'],
                                    'c' => $_POST['dilution'], 'd' => $_SESSION['id'],
                                ]);
                        ?>
                                <meta http-equiv="refresh" content="0">
                        <?php
                            }
                        }
                        ?>

                </div>
                <br>

                <h3 align="center"> Médicaments </h3>
                <br />
                <div class="table-responsive" style="position:relative;">
                    <table id="employee_data" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Conditionnement</th>
                                <th>Dilution</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php
                        while ($x = $allMedic->fetch()) {
                            if ($x['idMedicament'] != 0) {
                                echo "<tr><td>" . $x['nomMedicament'] .
                                    "</td><td>" . $x['conditionnement'] .
                                    "</td><td>" . $x['dilution'] .
                                    "</td><td>" . ' ' . '<form method="post" action="?">
                                                            <input type="hidden" name="idProp" value="' . $x['idMedicament'] . '" >
                                                            <input type="submit" name="delMedic" value="supprimer" style="background-color:red!important;border:hidden;">
                                                       </form>' .
                                    "</td></tr>";
                            }
                        }

                        if (isset($_POST['delMedic'])) {
                            $a = $_POST['idProp'];
                            $b = $_SESSION['id'];
                            $db->query("DELETE FROM medicament WHERE idMedicament=$a AND osteo_id=$b ");
                        ?>
                            <meta http-equiv="refresh" content="0">
                        <?php
                        }


                        ?>
                    </table>
                </div>
                <div id="bottom-bar">
                    <!-- logo univ-->
                    <p></p>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</body>

</html>
<script>
    $(document).ready(function() {
        $('#employee_data').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
            }
        });
    });
</script>