<?php
session_start(); // On démarre la session AVANT toute chose
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Osteo - login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="stylesheet" href="./style.css">

</head>

<body>

  <div id="contain-page">
    <!-- conteneur de ce qui contient du texte . En dehors = aucune interaction-->

    <?php include("./includes/header.php"); ?>

    <br><br><br>




    <article style="max-width: 500px;margin: 0 auto;">

      <div style="background-color: black; height: 2px;"></div>

      <br><br>
      <form action="./login.php" method="post" id="login" style="position: relative;margin: 0 auto; padding: 10px 20%;">
        <input style="position: relative;" type='text' name='username' id='username' placeholder="  Nom d'utilisateur" required><br><br>
        <input type='password' name='password' id='password' placeholder="  Mot de passe" required><br><br>
        <input style=" width: 300px; margin: 10px auto;" type="submit" name="log" id="log" value="Connexion" />
        <!--<a id="bouton-simple" href="./sign.php"><button type="button">Pas encore inscrit ?</button></a>-->
      </form>

      <center>
        <?php
        if (isset($_POST['log'])) {
          extract($_POST);
          if (empty($username)) {
            echo '<p style="color: red;"> Entrez un nom d\'utilisateur </p>';
          } elseif (empty($password)) {
            echo '<p style="color: red;"> Entrez un mot de passe</p>';
          } else {
            include './includes/database.php';
            global $db;

            $c = $db->prepare("SELECT * FROM users WHERE username= :username");
            $c->execute(['username' => $username]);
            $result = $c->fetch();

            if ($result) {
              $hashpass = $result['password'];

              if (password_verify($password, $hashpass)) {
                echo "Connexion réussie. Redirection...";
                $_SESSION['username'] = $result['username'];
                $_SESSION['date'] = $result['date'];
                $_SESSION['email'] = $result['email'];
                $_SESSION['id'] = $result['osteo_id'];
                $_SESSION['showContentProprio'] = "none";
                echo '<meta http-equiv="refresh" content="1;URL=./index.php">';
              } else {
                echo '<p style="color: red;">L\'email ou le mot de passe est incorrect</p>';
              }
            } else {
              echo '<p style="color: red;">L\'email ou le mot de passe est incorrect</p>';
            }
          }
        }

        ?>
      </center>



    </article>



    <div id="bottom-bar">
      <!-- logo univ-->
      <p></p>
    </div>

  </div>

  <!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src="./scripts/script.js"></script>
</body>

</html>