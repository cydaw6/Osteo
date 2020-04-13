<?php
session_start(); // On démarre la session AVANT toute chose
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta http-equiv="refresh" content="1; URL=./index.php" />
  <meta charset="UTF-8">
  <title>Osteo - Logout</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="stylesheet" href="./style.css">

</head>

<body>

  <?php // destruction instantanée de la session
  $_SESSION = array();
  session_destroy();
  ?>

  <div id="contain-page">
    <!-- conteneur de ce qui contient du texte . En dehors = aucune interaction-->

    <?php include("./includes/header.php"); ?>

    <br><br><br>

    <article>

      <div style="background-color: black; height: 2px;"></div>


      <?php
      if (isset($_SESSION['username']) && (isset($_SESSION['date']))) {
        echo 'Bienvenue ' . $_SESSION['username'];
      } else {
        echo '<p style="font-weight:bold;font-size:30px;"> Deconnexion ...<p>';
      }
      ?>
    </article>


    <br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br>

    <div id="bottom-bar">
      <!-- logo univ-->
      <p>Bla</p>
    </div>

  </div>



  <!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src="./scripts/script.js"></script>
</body>

</html>