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

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

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

        #infoBase>p {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div style="height: 158px;max-width: 100%;position: relative;z-index: 3;">

        <?php
        include './includes/button-to-top.php';
        include './includes/header.php';
        include './includes/right-navbar.php';
        include './includes/database.php'; // Connexion à la bdd
        ?>
    </div>
    <br /><br />

    <div style="padding-left: 170px; margin-right: 0px; margin-left: 0px; background-color:white; max-width: 100%;">
        <div class="container" style="background-color: white; max-width: 1060px; min-width: 100px!important;">
            <br>
            <br>
            <br>

            <?php
            // Attention ici la raison sociale devient un prenom et le type d'organisation le nom, pour simplifier (on récup une vue là)
            $a = $_SESSION['id'];
            $allAnimaux = $db->query("SELECT * FROM animal NATURAL JOIN nom_proprio WHERE osteo_id=$a");
            ?>
            <br>
            <div style="position: relative; padding : 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
                <div id="infoBase" style="margin-left:130px;">

                    <?php
                    $q = $db->query("SELECT COUNT(*) nbAnimaux FROM animal WHERE osteo_id=$a");
                    $r = $q->fetch();
                    echo '<p>Nombre d\'animaux enregistrés : ' . $r['nbAnimaux'] . '</p>';

                    $q = $db->query("SELECT COUNT(*) nbParticuliers FROM particulier NATURAL JOIN possede_proprio WHERE osteo_id=$a");
                    $r = $q->fetch();
                    echo '<p>Nombre de particuliers enregistrés : ' . $r['nbParticuliers'] . '</p>';

                    $q = $db->query("SELECT COUNT(*) nbOrga FROM organisme NATURAL JOIN possede_proprio WHERE osteo_id=$a");
                    $r = $q->fetch();
                    echo '<p>Nombre d\'organismes enregistrés : ' . $r['nbOrga'] . '</p>';

                    $q = $db->query("SELECT ROUND(AVG(prix),2) prixM FROM consultation NATURAL JOIN tarif WHERE osteo_id=$a");
                    $r = $q->fetch();
                    echo '<p>Prix moyen d\'une consultation : ' . $r['prixM'] . '€</p>';

                    $q = $db->query("SELECT COUNT(*) tt FROM consultation WHERE osteo_id=$a");
                    $r = $q->fetch();
                    echo '<p>Total de consultations réalisées: ' . $r['tt'] . '</p>';

                    ?>

                </div>
                <center>

                    <div class="container-fluid">
                        <div id="columnchart12" style="width: 100%; height: 250px;"></div>
                    </div>
                    <div class="container-fluid">
                        <div id="columnchart13" style="width: 100%; height: 250px;"></div>
                    </div>

                    <body>
                        <div id="chart_div" style="width: 100%; height: 500px;"></div>
                    </body>

            </div>
            <br>
            <div id="bottom-bar">
                <!-- logo univ-->
                <p></p>
            </div>
        </div>
    </div>

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

<script type="text/javascript">
    google.load("visualization", "1", {
        packages: ["corechart"]
    });
    google.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Type de la Consultation', 'nombre de consultation'],
            <?php
            $query = $db->query("SELECT typeConsultation, COUNT(idConsultation) nb FROM consultation NATURAL JOIN tarif WHERE osteo_id = $a GROUP BY typeConsultation");
            while ($row = $query->fetch()) {
                echo "['" . $row['typeConsultation'] . "'," . $row['nb'] . "],";
            }
            ?>
        ]);
        var options = {
            title: 'Nombre de consultations par types',
            pieHole: 0.5,
            pieSliceTextStyle: {
                color: 'black',
            },
            legend: 'none'
        };
        var chart = new google.visualization.PieChart(document.getElementById("columnchart12"));
        chart.draw(data, options);
    }
</script>
<script type="text/javascript">
    google.load("visualization", "1", {
        packages: ["corechart"]
    });
    google.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Lieu de la Consultation', 'nombre de consultation'],
            <?php
            $query = $db->query("SELECT lieuConsultation, COUNT(idConsultation) nb FROM consultation NATURAL JOIN tarif WHERE osteo_id = $a GROUP BY lieuConsultation");
            while ($row = $query->fetch()) {
                echo "['" . $row['lieuConsultation'] . "'," . $row['nb'] . "],";
            }
            ?>
        ]);
        var options = {
            title: 'Nombre de consultations par lieux ',
            pieHole: 0.5,
            pieSliceTextStyle: {
                color: 'black',
            },
            legend: 'none'
        };
        var chart = new google.visualization.PieChart(document.getElementById("columnchart13"));
        chart.draw(data, options);
    }
</script>
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Mois', 'Enregistr.'],
            <?php
            $query = $db->query("SELECT MONTH(dateAjout) d, COUNT(idAnimal) nb FROM animal  WHERE osteo_id = $a GROUP BY dateAjout");
            while ($row = $query->fetch()) {
                $allMonths = array(
                    '1' => "Janvier", '2' => "Fevrier", '3' => "Mars", '4' => "Avril", '5' => "Mai", '6' => "Juin", '7' => "Juillet", '8' => "Aout", '9' => "Septembre", '10' => "Octobre", '11' => "Novembre", '12' => "Decembre"
                );
                echo "['" . $allMonths[$row['d']] . "'," . $row['nb'] . "],";
            }
            ?>
        ]);

        var options = {
            title: 'Enregistrement d\'animaux/mois de l\'année en cours',
            hAxis: {
                title: 'Mois',
                titleTextStyle: {
                    color: '#333'
                }
            },
            vAxis: {
                minValue: 0
            }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>