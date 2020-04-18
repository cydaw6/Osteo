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
        } elseif (!isset($_POST['detailConsultation'])) {
            echo '<h4>Consultation non définie.</h4>';
        } else {

        ?>
            <div class="container" style=" color:white;">
                <?php
                include './includes/database.php';
                echo '<b>Détail de la consultation</b><br>';
                $a = $_SESSION['id'];
                $b = $_POST['idConsultation'];
                $consultation = $db->query("SELECT * FROM `consultation` c 
                                                JOIN animal 
                                                JOIN tarif 
                                                JOIN nom_proprio 
                                                WHERE animal.idAnimal = c.idAnimal 
                                                    AND tarif.idTarif = c.idTarif 
                                                    AND animal.idProprietaire = nom_proprio.idProprietaire 
                                                    AND c.osteo_id=$a 
                                                    AND idConsultation=$b");
                $t =  $consultation->fetch();
                ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom de l'animal</th>
                            <th>Proprietaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            echo '<td>' . $t['nomAnimal'] . '</td>
                            <td>' . $t['nom'] . ' ' . $t['prenom'] . '</td>
                           ';
                            ?>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Durée</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            echo '<td>' . $t['date'] . '</td>
                            <td>' . $t['dureeConsultation'] . '</td> ';
                            ?>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <th>Anamnese</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            echo '
                            <td>' . $t['anamnese'] . '</td>';
                            ?>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <th>Suivi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            echo ' 
                            <td>' . $t['suivi'] . '</td> ';
                            ?>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <th>Diagnostic</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            echo ' 
                            <td>' . $t['diagnostic'] . '</td>';
                            ?>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <th>Manipulations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            echo ' 
                            <td>' . $t['resumManip'] . '</td> ';
                            ?>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <th>Lieu de la consultation</th>
                            <th>Type de la consultation</th>
                            <th>Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            echo ' 
                            <td>' . $t['lieuConsultation'] . '</td> 
                            <td>' . $t['typeConsultation'] . '</td> 
                            <td>' . $t['prix'] . ' €</td>';
                            ?>
                        </tr>
                    </tbody>
                </table>

                <br>
                <br>

                <?php echo '<b>Liste des traitements préscrit </b>';  ?>
                <table>
                    <thead>
                        <tr>
                            <th>Medicament</th>
                            <th>Produit</th>
                            <th>Fréquence</th>
                            <th>Dose</th>
                            <th>Durée du traitement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $c = $_POST['idConsultation'];
                        $allTraitements = $db->query("SELECT * FROM `traitement` NATURAL JOIN medicament WHERE idConsultation=$c");
                        while ($x = $allTraitements->fetch()) {
                            echo '<tr>
                            <td>' . $x['nomMedicament'] . '</td>
                            <td>' . $x['produit'] . '</td>
                            <td>' . $x['frequence'] . '</td>
                            <td>' . $x['dose'] . '</td>
                            <td>' . $x['dureeTraitement'] . '</td>
                            </tr>';
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