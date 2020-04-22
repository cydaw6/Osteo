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
  <link rel="stylesheet" href="./style-post.css">
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
          <?php
          if (isset($_POST['profil'])) {
            include './includes/submenus/profile.php';
            // <p> azeazeaz<br><br><br><br><br><br><br>jhhkjhkj<br><br><br>qzeerzer</p> 
          }
          if (isset($_SESSION['username']) && (isset($_SESSION['date']))) {
            if (!isset($_POST['profil'])) {
              echo '<h2>Fil d\'actualité</h2>
            <br><br><br><br> <center>';
              $ex = $db->query("SELECT * FROM posts ORDER BY datePost DESC");
              while ($t = $ex->fetch()) {
                echo '<div class="timeline__item">';
                echo '<h2>' . $t['titre'] . '</h2><br>';
                echo '<p>' . $t['contenu'] . '</p>';
                echo '<p style="font-size:10px;"> posté le ' . $t['datePost'] . '</p> </div>';
              }
            }
          } else {
            echo '<h2>OSTEO</h2>
                		<div style="background-color: black; height: 2px;"></div>';
            echo '<p>   </p>';
            echo  "<p>Inscrivez vous et diposez d'une interface simple pour gérer les données de vos consultations et de vos patients.</p>";
          }
          ?>
        </article>
      </div>
      <div id="bottom-bar" style="margin-top: 500px;">
        <!-- logo univ-->
        <p></p>
      </div>
  </div>
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