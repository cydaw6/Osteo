<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Osteo - Sign in</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="stylesheet" href="./style.css">

</head>

<body>

  <div id="contain-page">
    <!-- conteneur de ce qui contient du texte . En dehors = aucune interaction-->

    <?php include("./includes/header.php"); ?>

    <br><br><br>

    <article style="max-width: 500px;margin: 0 auto;">
      <h2></h2>
      <div style="background-color: black; height: 2px;"></div>

      <br><br>

      <article style="max-width: 500px;margin: 0 auto;">
        <form action="./sign.php" method="post" id="sign" style="position: relative;margin: 0 auto; padding: 10px 20%;">
          <input type='text' name="email" id="email" placeholder="  Adresse email" required><br><br>
          <input type='text' name="username" id="username" placeholder="  Nom d'utilisateur" required><br><br>
          <input type='password' name="password" id="password" placeholder="  Mot de passe" required><br><br>
          <input type='password' name="password_bis" id="password_bis" placeholder="  Confirmez le mot de passe" required><br><br>

          <input style=" width: 300px; margin: 10px auto;" type="submit" name="sign" id="sign" value="S'inscire" />
          <!--<a id="bouton-simple" href="./login.php"><button type="button">Pas encore inscrit ?</button></a>-->

        </form>

        <center style="color:red;">
          <?php
          if (isset($_POST['sign'])) {

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
            } elseif (empty($password)) {
              echo 'Entrez un mot de passe';
            } elseif (empty($password_bis)) {
              echo ' Confirmez le mot de passe';
            } elseif ($password != $password_bis) {
              echo ' Les mots de passes ne sont pas identiques';
            } elseif (strlen($password) < 8) {
              echo ' Votre mot de passe doit contenir huits caractères minimum ';
            } else {

              $options = ['cost' => 13,]; // durée du hashage
              $hashpass = password_hash($password, PASSWORD_BCRYPT, $options);

              include './includes/database.php'; // connexion à la bdd
              global $db;

              $c = $db->prepare("SELECT email FROM users WHERE email= :email");
              $c->execute(['email' => $email]);
              $result = $c->rowCount();

              $c2 = $db->prepare("SELECT username FROM users WHERE username= :username");
              $c2->execute(['username' => $username]);
              $result2 = $c2->rowCount();

              if ($result == 0 && $result2 == 0) {
                $secure_username = str_replace(array("\n", "\r", PHP_EOL), '', $username);
                $secure_email = str_replace(array("\n", "\r", PHP_EOL), '', $email); // on sécurise le mail pour éviter les injections de retour de lignes même si filter s'en occupe

                $q = $db->prepare("INSERT INTO users VALUES(DEFAULT, :username,:email,:password, DEFAULT, 0)");


                $q->execute([
                  'username' => $secure_username,
                  'email' => $secure_email,
                  'password' => $hashpass,
                ]);
                echo '<meta http-equiv="refresh" content="2;URL=./login.php">';
                echo '<p style="color:black;">Inscription réussie.</p>';
              } elseif ($result != 0) {
                echo "Cet email est déjà utilisé";
              } elseif ($result2 != 0) {
                echo "Ce pseudo est déjà pris";
              }
            }
          }
          ?>
        </center>

      </article>


  </div>
  <div style="background: white;">

    <div id="bottom-bar" style="width: 100%;margin:-15px;">
      <!-- logo univ-->
      <p></p>
    </div>


  </div>


  <!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src="./scripts/script.js"></script>
</body>

</html>