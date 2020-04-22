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
            $a = $_SESSION['id'];
            $allUsers = $db->query("SELECT * FROM users WHERE osteo_id!=$a AND admin!=1");
            ?>
        </div>
        <br /><br />

        <div style="padding-left: 170px; margin-right: 0px; margin-left: 0px; background-color:white; max-width: 100%;">

            <div class="container" style="background-color: white; max-width: 1060px; min-width: 100px!important;">
                <?php
                if (isset($_POST['modifUser'])) {
                    $w = $_POST['idUser'];
                    $f = $db->query("SELECT * FROM users WHERE osteo_id=$w");
                    $z = $f->fetch();
                ?>
                    <br>
                    <br>
                    <br>
                    <center>
                        <div style="position: relative; padding : 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
                            <form action="?" method="post">
                                Modifier l'utilisateur
                                <br>
                                <input type='text' name=" email" id="email" <?php echo 'value="' . $z['email'] . '"';  ?> required><br><br>
                                <input type='text' name="username" id="username" <?php echo 'value="' . $z['username'] . '"';  ?> required><br><br>
                                Droits administrateur:
                                <?php
                                if ($z['admin'] == 0) {
                                    echo '<input type="radio" id="1" name="admin" value="0" checked>';
                                } else {
                                    echo '<input type="radio" id="1" name="admin" value="0">';
                                }
                                ?>
                                <label for="1"><img src="./img/red-cross.png" style="width:20px;"></label>
                                <?php
                                if ($z['admin'] == 1) {
                                    echo '<input type="radio" id="2" name="admin" value="1" checked>';
                                } else {
                                    echo '<input type="radio" id="2" name="admin" value="1">';
                                }
                                ?>
                                <label for="2"><img src="./img/green-cross.png" style="width:20px;"></label>
                                <br>
                                <input hidden name="modifUser">
                                <input hidden name="idUser" <?php echo 'value="' . $_POST['idUser'] . '"';  ?>>
                                <input style=" width: 100px; margin: 10px auto;" type="submit" name="subModif" value=" Modifier" />
                            </form>
                            <form action="?" method="post">
                                <input style=" width: 100px; margin: 10px auto;" type="submit" value="Annuler" />
                            </form>
                            <br>
                            <form action="?" method="post">
                                <input hidden name="idUser" <?php echo 'value="' . $_POST['idUser'] . '"';  ?>>
                                <input hidden name="uname" <?php echo 'value="' . $_POST['uname'] . '"';  ?>>
                                <input hidden name="modifUser">
                                <input type='password' name="password" placeholder="Modifier le mot de passe"><br><br>
                                <input style=" width: 100px; margin: 10px auto;" type="submit" name="submitMdp" value=" Modifier" />
                            </form>

                            <?php
                            if (isset($_POST['subModif'])) {
                                extract($_POST);
                                if (empty($email)) {
                                    echo 'Entrez une adresse email';
                                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    echo 'Entrez une adresse email valide';
                                } elseif (empty($username)) {
                                    echo 'Entrez un nom d\'utilisateur';
                                } elseif (preg_match('/[A-Z]+/', $username)) {
                                    echo 'Votre pseudo ne doit pas contenir de majuscules';
                                } elseif (preg_match('#(?=.*\W)#', $username)) {
                                    echo 'Votre pseudo ne doit pas contenir de caractères spéciaux';
                                } else {
                                    $c = $db->prepare("SELECT email FROM users WHERE email= :email AND osteo_id!=:userId");
                                    $c->execute(['email' => $email, 'userId' => $idUser]);
                                    $result = $c->rowCount();

                                    $c2 = $db->prepare("SELECT username FROM users WHERE username= :username AND osteo_id!=:userId");
                                    $c2->execute(['username' => $username, 'userId' => $idUser]);
                                    $result2 = $c2->rowCount();

                                    if ($result == 0 && $result2 == 0) {
                                        $q = $db->prepare("UPDATE users SET username=:username, email=:email, admin=:ad WHERE osteo_id=:id");
                                        $q->execute([
                                            'username' => $username,
                                            'email' => $email,
                                            'ad' => $admin,
                                            'id' => $idUser
                                        ]);
                            ?>
                                        <meta http-equiv="refresh" content="0">
                                <?php
                                    } elseif ($result != 0) {
                                        echo "Cet email est déjà utilisé";
                                    } elseif ($result2 != 0) {
                                        echo "Ce pseudo est déjà pris";
                                    }
                                }
                            }
                            if (isset($_POST['submitMdp'])) {
                                $options = ['cost' => 13,]; // durée du hashage
                                $passw = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
                                $q = $db->prepare("UPDATE users SET password=:pssw WHERE username=:uname");
                                $q->execute([
                                    'pssw' => $passw,
                                    'uname' => $_POST['uname']
                                ]);
                                ?>
                                <meta http-equiv="refresh" content="0">
                            <?php
                            }
                            ?>
                        </div>
                    <?php
                }
                    ?>
                    <br>
                    <br>
                    <br>
                    <h3 align="center"> Utilisateurs </h3>
                    <br />
                    <div class="table-responsive" style="position:relative;">
                        <table id="employee_data" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>email</th>
                                    <th>Date d'inscription</th>
                                    <th>Droits administrateur</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php
                            while ($x = $allUsers->fetch()) {
                                echo "<tr><td>" . $x['username'] .
                                    "</td><td>" . $x['email'] .
                                    "</td><td>" . $x['date'];
                                if ($x['admin'] == 0) {
                                    echo '</td><td><img src="./img/red-cross.png" style="width:20px;">';
                                } else {
                                    echo '</td><td><img src="./img/green-cross.png" style="width:20px;">';
                                }
                                echo
                                    "</td><td>" . ' ' . '<form method="post" action="?">
                                                            <input type="hidden" name="idUser" value="' . $x['osteo_id'] . '" >
                                                            <input type="hidden" name="uname" value="' . $x['username'] . '" >
                                                            <input type="submit" name="modifUser" value="Modifier" style="background-color:#834fa8!important;border:hidden;">
                                                       </form>

                                                       <form method="post" action="?">
                                                       <input type="hidden" name="idUser" value="' . $x['osteo_id'] . '" >
                                                            <input type="submit" name="delUser" value="supprimer" style="background-color:red!important;border:hidden;">
                                                      
                                                       </form> ' .
                                        "</td></tr>";
                            }
                            if (isset($_POST['delUser'])) {
                                $a = $_POST['idUser'];
                                $db->query("DELETE traitement FROM traitement JOIN animal a ON (traitement.idAnimal= a.idAnimal) WHERE a.osteo_id=$a");
                                $db->query("DELETE FROM consultation WHERE osteo_id=$a");
                                $db->query("DELETE FROM medicament WHERE osteo_id=$a");
                                $db->query("DELETE FROM lieu_consultation WHERE osteo_id=$a");
                                $db->query("DELETE FROM type_consultation WHERE osteo_id=$a");
                                $db->query("DELETE FROM tarif WHERE osteo_id=$a");
                                $db->query("DELETE FROM animal WHERE osteo_id=$a");
                                $db->query("DELETE a_contacter FROM a_contacter NATURAL JOIN proprietaire WHERE osteo_id=$a");
                                $db->query("DELETE organisme FROM organisme NATURAL JOIN proprietaire WHERE osteo_id=$a");
                                $db->query("DELETE particulier FROM particulier NATURAL JOIN proprietaire WHERE osteo_id=$a");
                                $db->query("DELETE FROM proprietaire WHERE osteo_id=$a");
                                $db->query("DELETE FROM users WHERE osteo_id=$a");

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