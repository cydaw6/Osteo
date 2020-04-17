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
     </style>
</head>

<body>

     <div style="height: 158px;max-width: 100%;position: relative;z-index: 3;">

          <?php
          include './includes/header.php';
          include './includes/right-navbar.php';
          include './includes/database.php'; // Connexion à la bdd
          $idosteo = $_SESSION['id'];
          /*
          $tableAnimal = $db->query("CREATE VIEW nom_proprio AS
SELECT idProprietaire, raisonSociale prenom, typeOrga nom, osteo_id FROM proprietaire NATURAL JOIN organisme NATURAL JOIN possede_proprio
UNION
SELECT idProprietaire, prenomPa, nomPa, osteo_id FROM proprietaire NATURAL JOIN particulier NATURAL JOIN possede_proprio 
                                   ");*/


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
               <br>
               <br>

               <?php
               // Attention ici la raison sociale devient un prenom et le type d'organisation le nom, pour simplifier (on récup une vue là)
               $a = $_SESSION['id'];
               $allProprio = $db->query("SELECT * FROM nom_proprio WHERE osteo_id=$a ORDER BY nom");
               $allAnimaux = $db->query("SELECT * FROM animal NATURAL JOIN nom_proprio WHERE osteo_id=$a");
               ?>
               <br>
               <div style="position: relative; padding : 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
                    <center>
                         <br>
                         Ajouter un animal
                         <br>
                         <br>
                         <form method="post" action="?">
                              Propriétaire
                              <select name="idproprio" required>
                                   <option value=""> None</option>
                                   <?php
                                   while ($t = $allProprio->fetch()) {
                                        echo '<option value="' . $t['idProprietaire'] . '">' . $t['nom'] . '-' . $t['prenom'] . '</option>';
                                   }
                                   ?>
                              </select><br>
                              <input type="text" name="nom" placeholder="nom" required><br>
                              <input type="text" name="espece" placeholder="espece" required><br>
                              <input type="text" name="race" placeholder="race" required><br>
                              Sexe:
                              <select name="sexe" required>
                                   <option value="">None</option>
                                   <option value="m">Mâle</option>
                                   <option value="f">Femelle</option>
                              </select>
                              <label for="castration">Castration:</label>
                              <select name="castration">
                                   <option value="n">non</option>
                                   <option value="o">oui</option>
                              </select><br>
                              <input type="text" name="taille" placeholder="Taille (22.55)" required><br>
                              <input type="text" name="poids" placeholder="Poids (2.55)" required><br>
                              <p>Anamnese</p>
                              <textarea id="story" name="anamnese" rows="5" cols="33"></textarea>
                              <br>
                              <input type="submit" name="subAn" value="Ajouter">
                              <input type="reset" value="Effacer" onAction=>
                              <br>
                              <br>
                         </form>
                         <?php
                         if (isset($_POST['subAn'])) {
                              extract($_POST);
                              if (
                                   containsSpecialChars($nom) || containsSpecialChars($espece) ||
                                   containsSpecialChars($race) || containsSpecialChars($taille) ||
                                   containsSpecialChars($poids)
                              ) {
                                   echo 'Les caractères spéciaux ne sont pas autorisés';
                              } elseif (containsNumber($race) || containsNumber($espece)) {
                                   echo 'La race ou l\'espèce ne peuvent contenir des nombres';
                              } elseif (!is_numeric($taille) || !is_numeric($poids)) {
                                   echo 'Le poids et la taille doivent être un nombre entier ou réel';
                              } else {
                                   $doubleAnimal = $db->prepare("SELECT * FROM animal WHERE nomAnimal= :nom AND espece= :espece AND race= :race AND idProprietaire= :proprioId AND osteo_id= :osteoId");
                                   $doubleAnimal->execute(['nom' => $nom, 'espece' => $espece, 'race' => $race, 'proprioId' => $idproprio, 'osteoId' => $_SESSION['id']]);
                                   $result = $doubleAnimal->rowCount();
                                   if ($result >= 1) { # verification dans la base unique de l'osteo
                                        echo 'Vous avez déjà enregistré cet animal pour ce propriétaire';
                                   } else {
                                        $createAnimal = $db->prepare("INSERT INTO animal VALUES(DEFAULT, :a,:b, :c, :d, :e, :f, :g, :h, :i, :j)");
                                        $createAnimal->execute([
                                             'a' => $nom, 'b' => $espece,
                                             'c' => $race, 'd' => $taille,
                                             'e' => $poids, 'f' => $sexe,
                                             'g' => $castration, 'h' => $anamnese,
                                             'i' => $idproprio, 'j' => $_SESSION['id']
                                        ]);
                         ?>
                                        <meta http-equiv="refresh" content="0">
                         <?php
                                   }
                              }
                         }
                         ?>

               </div>
               <br>

               <h3 align="center"> Animaux </h3>
               <br />
               <div class="table-responsive" style="position:relative;">
                    <table id="employee_data" class="table table-striped table-bordered">
                         <thead>
                              <tr>
                                   <th>Nom</th>
                                   <th>Espèce</th>
                                   <th>Race</th>
                                   <th>Taille</th>
                                   <th>Poids</th>
                                   <th>Sexe</th>
                                   <th>Castration</th>
                                   <th>Propriétaire</th>
                                   <th>Action</th>

                              </tr>
                         </thead>
                         <?php
                         while ($x = $allAnimaux->fetch()) {
                              $s = ($x['sexe'] == "f") ? 'Femelle' : 'Mâle';
                              $c = ($x['castration'] == "o") ? 'oui' : 'non';

                              echo "<tr><td>" . $x['nomAnimal'] .
                                   "</td><td>" . $x['espece'] .
                                   "</td><td>" . $x['race'] .
                                   "</td><td>" . $x['taille'] . ' cm' .
                                   "</td><td>" . $x['poids'] . ' kg' .
                                   "</td><td>" . $s .
                                   "</td><td>" . $c .
                                   "</td><td>" . $x['nom'] . '  ' . $x['prenom'] .
                                   "</td><td>" . ' ' . '<form method="post" action="./animaux.php">
                                                            <input type="hidden" name="idAnimal" value="' . $x['idAnimal'] . '" >
                                                            <input type="submit" name="majAnimal" value="modifier">
                                                            <input type="submit" name="delAnimal" value="supprimer">
                                                       </form>' .
                                   "</td></tr>";
                         }
                         ?>
                    </table>

                    <?php
                    if (isset($_POST['delAnimal'])) {
                         $x = $_POST['idAnimal'];
                         $db->query("DELETE FROM animal WHERE idAnimal=$x");
                    ?>
                         <meta http-equiv="refresh" content="0">
                    <?php
                    }


                    ?>
               </div>
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