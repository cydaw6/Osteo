<?php
session_start(); // On démarre la session AVANT toute chose
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Osteo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

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
    </style>



</head>

<body>
    <div id="contain-page">
        <!-- conteneur de ce qui contient du texte . En dehors = aucune interaction-->
        <?php include("./includes/header.php"); ?>
        <div style="height: 443px;">
            <div style="position: absolute;z-index: 3;">
                <?php
                if (isset($_SESSION['username']) && (isset($_SESSION['date']))) {
                    include("./includes/right-navbar.php");
                    include './includes/database.php';
                } else {
                }
                ?>
            </div>
        </div>
        <center>
            <div style="max-width: 932px;margin-top:-398px;z-index: 1;">
                <article style="background: white;">
                    <br>
                    <br>
                    <div style="background-color: black; height: 2px;"></div>
                    <br>
                    <h4>
                        L'interface OSTEO est un projet mené par un binôme d'étudiants de 1ère année du DUT informatique de l'université Gustave Eiffel.
                    </h4>
                    <br>
                    <br>
                    <a href="https://www.univ-gustave-eiffel.fr/" target="_blank"><img src="./img/logo-univ2.png" style="width:300px;"></a>
                    <br><br><br>

                    <p style="color: black; font-size:9px">
                        OSTEO 2019-2020</p>
                </article>
            </div>
            <div id="bottom-bar" style="margin-top: 500px;">
                <!-- logo univ-->
                <p></p>
            </div>
    </div>
    <!-- 932px; border: 3px black solid;-->
    <!-- partial -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="./scripts/script.js"></script>
</body>

</html>
<script>
    $(document).ready(function() {
        $('#employee_data').DataTable();
    });
</script>