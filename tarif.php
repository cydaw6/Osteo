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
            background-color: #a3a3a3;
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
            $allAnimals = $db->query("SELECT idAnimal, nomAnimal, espece, nom, prenom FROM animal an NATURAL JOIN nom_proprio WHERE an.osteo_id=$a");

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

                <br>
                <div style="position: relative; padding : 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
                    <center>
                        <br>
                        <br>
                        <br>
                        <br>
                        Ajouter un nouveau tarif

                        <form method="post" action="?">
                            <select name="see" onchange="this.form.submit()">
                                <option value="">Vue</option>
                                <option value="rien">Ne rien voir</option>
                                <option value="seeLieu">Voir les lieux</option>
                                <option value="seeType">Voir les types de consultations</option>
                            </select>
                            <input type="submit" name="subSee" hidden>
                        </form>

                        <br>
                        <form method="post" action="?">
                            <input type="text" name="addLieu" placeholder="Ajouter un lieu" required>
                            <input type="submit" name="subLieu" value="Ajouter">
                        </form>

                        <form method="post" action="?">
                            <input type="text" name="addType" placeholder="Ajouter un type de consultation" required>
                            <input type="submit" name="subType" value="Ajouter">
                        </form>

                        <?php
                        /* AJOUT DE LIEU OU TYPE DE CONSULT*/
                        if (isset($_POST['subLieu'])) {
                            $prep = $db->prepare("INSERT INTO lieu_consultation VALUES(DEFAULT, :a, :b)");
                            $prep->execute(['a' => $_POST['addLieu'], 'b' => $_SESSION['id']]);
                        } elseif (isset($_POST['subType'])) {
                            $prep = $db->prepare("INSERT INTO type_consultation VALUES(DEFAULT, :a, :b)");
                            $prep->execute(['a' => $_POST['addType'], 'b' => $_SESSION['id']]);
                        }

                        /* VOIR LISTE LIEU OU TYPE DE CONSULT*/
                        if (isset($_POST['see'])) {
                            $_SESSION['showTypeAndPlace'] = $_POST['see'];
                        ?>
                            <meta http-equiv="refresh" content="0">
                            <?php
                        }

                        if ($_SESSION['showTypeAndPlace'] == "seeLieu") {

                            $a = $_SESSION['id'];
                            $result = $db->query("SELECT * FROM lieu_consultation WHERE osteo_id=$a ORDER BY lieuConsultation");
                            if ($result->rowCount() >= 1) {
                                echo '<table id="describeAnimal">
                                        <tr>
                                            <th>Lieux de Consultation</th>
                                            <th>Action</th>
                                        <tr>';
                                while ($t = $result->fetch()) {
                                    echo '<tr>
                                 <td>' . $t['lieuConsultation'] . '</td>
                                        <td><form method="post" action="?">
                                            <input type="hidden" name="idLieu" value="' . $t['id_lieu'] . '">
                                            <input type="submit" name="delLieu" value="supprimer" style="background-color:red!important;border:hidden;">
                                            </form>
                                        </td>
                                </tr>
                                ';
                                }
                                echo '</table>';
                            }
                            if (isset($_POST['delLieu'])) {
                                $prep = $db->prepare("DELETE FROM lieu_consultation WHERE id_lieu=:c AND osteo_id=:d");
                                $prep->execute(['c' => $_POST['idLieu'], 'd' => $_SESSION['id']]);

                            ?>
                                <meta http-equiv="refresh" content="0">
                            <?php
                            }
                        } elseif ($_SESSION['showTypeAndPlace'] == "seeType") {
                            $a = $_SESSION['id'];
                            $result = $db->query("SELECT * FROM type_consultation WHERE osteo_id=$a ORDER BY typeConsultation");
                            if ($result->rowCount() >= 1) {
                                echo '<table id="describeAnimal">
                              <tr>
                                   <th>Types de Consultation</th>
                                   <th>Action</th>
                              <tr>';
                                while ($x = $result->fetch()) {
                                    echo '
                                    <tr> <td>' . $x['typeConsultation'] . ' ' . $x['id_type'] . '</td>
                                    <td>
                                        <form method="post" action="?">
                                            <input type="hidden" name="idType" value="' . $x['id_type'] . '" >
                                            <input type="submit" name="delType" value="supprimer" style="background-color:red!important;border:hidden;">
                                        </form>
                                </td>
                                </tr>';
                                }
                                echo '</table>';
                            }

                            if (isset($_POST['delType'])) {
                                $prep = $db->prepare("DELETE FROM type_consultation WHERE id_type=:c AND osteo_id=:d");
                                $prep->execute(['c' => $_POST['idType'], 'd' => $_SESSION['id']]);
                            ?>
                                <meta http-equiv="refresh" content="0">
                        <?php
                            }
                        }


                        ?>


                        <form method="post" action="?">
                            <input type="text" name="prix" placeholder="Prix (ex: 65.78)" required>
                            <select name="lieuConsult" required>
                                <option value="">None</option>
                                <?php
                                $a = $_SESSION['id'];
                                $result = $db->query("SELECT * FROM lieu_consultation WHERE osteo_id=$a ORDER BY lieuConsultation");
                                while ($t = $result->fetch()) {
                                    echo '<option value="' . $t['lieuConsultation'] . '">' . $t['lieuConsultation'] . '</option>';
                                }
                                ?>
                            </select>

                            <select name="typeConsult" required>
                                <option value="">None</option>
                                <?php
                                $a = $_SESSION['id'];
                                $result = $db->query("SELECT * FROM type_consultation WHERE osteo_id=$a ORDER BY typeConsultation");
                                while ($t = $result->fetch()) {
                                    echo '<option value="' . $t['typeConsultation'] . '">' . $t['typeConsultation'] . '</option>';
                                }
                                ?>
                            </select>
                            <br>
                            <input type="submit" name="subTarif" value="Ajouter">
                            <br>
                            <br>
                        </form>
                        <?php
                        if (isset($_POST['subTarif'])) {
                            if (!is_numeric($_POST['prix'])) {
                                echo 'Le prix doit être un nombre entier ou réel';
                            } else {
                                $result = $db->prepare("SELECT * FROM tarif WHERE osteo_id=:a AND lieuConsultation=:b AND typeConsultation=:c");
                                $result->execute(['a' => $_SESSION['id'], 'b' => $_POST['lieuConsult'], 'c' => $_POST['typeConsult']]);

                                if ($result->rowCount() > 0) {
                                    echo 'Ce tarif est déjà enregistré';
                                } else {
                                    $result = $db->prepare("INSERT INTO tarif VALUES(DEFAULT, :a, :b, :c, :d)");
                                    $result->execute(['a' => $_POST['prix'], 'b' => $_POST['lieuConsult'], 'c' => $c = $_POST['typeConsult'], 'd' => $_SESSION['id']]);
                                }
                            }
                        }
                        ?>
                </div>
                <br>
                <br>

                <h3 align="center"> Liste des tarifs </h3>
                <br />
                <div class="table-responsive" style="position:relative;">
                    <table id="employee_data" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Lieux de Consultation</th>
                                <th>Types de consultation</th>
                                <th>Prix</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php
                        $a = $_SESSION['id'];
                        $allTarifs = $db->query("SELECT * FROM tarif WHERE osteo_id=$a");

                        while ($x = $allTarifs->fetch()) {
                            echo "<tr><td>" . $x['lieuConsultation'] .
                                "</td><td>" . $x['typeConsultation'] .
                                "</td><td>" . $x['prix'] . ' €' .
                                "</td><td>" . ' ' . '<form method="post" action="?">
                                                            <input type="hidden" name="idTarif" value="' . $x['idTarif'] . '" >
                                                            <input type="submit" name="delTarif" value="supprimer" style="background-color:red!important;border:hidden;">
                                                       </form>' .
                                "</td></td>";
                        }
                        if (isset($_POST['delTarif'])) {

                            $result = $db->prepare("DELETE FROM tarif WHERE osteo_id=:a AND idTarif=:b");
                            $result->execute(['a' => $_SESSION['id'], 'b' => $_POST['idTarif']]);
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