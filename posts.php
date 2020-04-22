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
    if ($_SESSION['isAdmin'] != true) {
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
            $allPosts = $db->query("SELECT * FROM posts NATURAL JOIN users");
            ?>
        </div>
        <br /><br />
        <div style="padding-left: 170px; margin-right: 0px; margin-left: 0px; background-color:white; max-width: 100%;">
            <div class="container" style="background-color: white; max-width: 1060px; min-width: 100px!important;">
                <br>
                <br>
                <br>
                <center>
                    <div style="position: relative; padding : 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
                        <form action="?" method="post">
                            Créer un post
                            <br>
                            <br>
                            Titre
                            <br>
                            <input type="text" name="titre" size="2" required><br><br>
                            Contenu du Post
                            <br>
                            <textarea name="contenu" rows="5" cols="33"></textarea>
                            <br>
                            <input hidden name="modifUser">
                            <input hidden name="idUser">
                            <input style=" width: 100px; margin: 10px auto;" type="submit" name="subPost" value=" Poster" />
                            <input style=" width: 100px; margin: 10px auto;" type="reset" value=" Effacer" style="background-color:red!important;border:hidden;" />
                        </form>
                        <br>
                        <?php
                        if (isset($_POST['subPost'])) {
                            $prep = $db->prepare("INSERT INTO posts VALUES(DEFAULT, :titre, :contenu, DEFAULT, :osteoId)");
                            $prep->execute(['titre' => $_POST['titre'], 'contenu' => $_POST['contenu'], 'osteoId' => $_SESSION['id']]);
                        ?>
                            <meta http-equiv="refresh" content="0">
                        <?php
                        }
                        ?>
                    </div>

                    <br>
                    <br>
                    <br>
                    <h3 align="center"> Posts </h3>
                    <br />
                    <div class="table-responsive" style="position:relative;">
                        <table id="employee_data" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Contenu</th>
                                    <th>Date</th>
                                    <th>Date d'inscription</th>
                                    <th>Admin</th>
                                </tr>
                            </thead>
                            <?php
                            while ($x = $allPosts->fetch()) {
                                echo "<tr><td>" . $x['titre'] .
                                    "</td><td>" . $x['contenu'] .
                                    "</td><td>" . $x['datePost'] .
                                    "</td><td>" . $x['username'] .
                                    "</td><td>" . ' ' .
                                    '<form method="post" action="?">
                                            <input type="hidden" name="idPost" value="' . $x['idPost'] . '" >
                                            <input type="submit" name="delPost" value="supprimer" style="background-color:red!important;border:hidden;">
                                        
                                        </form> ' .
                                    "</td></>";
                            }
                            if (isset($_POST['delPost'])) {
                                $a = $_POST['idPost'];
                                $db->query("DELETE FROM posts WHERE idPost = $a");
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