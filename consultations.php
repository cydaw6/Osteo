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

     <div style="height: 158px;max-width: 100%;position: relative;z-index: 3;">

          <?php
          include './includes/header.php';
          include './includes/right-navbar.php';
          include './includes/database.php'; // Connexion à la bdd
          $a = $_SESSION['id'];
          $allAnimals = $db->query("SELECT idAnimal, nomAnimal, espece, nom, prenom FROM animal an NATURAL JOIN nom_proprio WHERE an.osteo_id=$a");

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
               <form method="post" action="?" style="position:relative;">
                    <select name="choiceAnimal" onchange="this.form.submit()">
                         <option>Animal</option>
                         <?php
                         while ($t = $allAnimals->fetch()) {
                              echo '<option value="' . $t['idAnimal'] . '">' . $t['nomAnimal'] . ' | ' . $t['nom'] . ' - ' . $t['prenom'] . '</option>';
                         }
                         ?>
                         <option hidden> --- -- --- -----ooooooooooooooooooooooooooooooooo o o o o o o o o oo o o-- ---- --- ----- -- -- - </option>
                    </select>
                    <input type="submit" name="choixAnimal" hidden>
                    <?php
                    if (isset($_POST['choiceAnimal'])) {
                         $_SESSION['choixAnimalConsult'] = $_POST['choiceAnimal'];
                    }

                    ?>
               </form>

               <?php
               // Attention ici la raison sociale devient un prenom et le type d'organisation le nom, pour simplifier (on récup une vue là)
               $allProprio = $db->query("SELECT * FROM nom_proprio");
               $a = $_SESSION['id'];
               $allAnimaux = $db->query("SELECT * FROM animal NATURAL JOIN nom_proprio WHERE osteo_id=$a");
               echo '<br>';
               if ($_SESSION['choixAnimalConsult'] != "-1") {
                    $idAnimal = $_SESSION['choixAnimalConsult'];
                    $infoAnimal = $db->query("SELECT * FROM `animal` NATURAL JOIN nom_proprio WHERE idAnimal=$idAnimal");

                    echo '<table id="describeAnimal">
                              <tr>
                                   <th>Nom</th>
                                   <th>Espèce</th>
                                   <th>Race</th>
                                   <th>Taille</th>
                                   <th>Poids</th>
                                   <th>Sexe</th>
                                   <th>Castration</th>
                                   <th>Propriétaire</th>
                              <tr>';
                    while ($x = $infoAnimal->fetch()) {
                         $s = ($x['sexe'] == "f") ? 'Femelle' : 'Mâle';
                         $c = ($x['castration'] == "o") ? 'oui' : 'non';

                         echo "
                              <tr>
                                   <td>" . $x['nomAnimal'] .
                              "</td>
                                   <td>" . $x['espece'] .
                              "</td>
                                   <td>" . $x['race'] .
                              "</td>
                                   <td>" . $x['taille'] . ' cm' .
                              "</td>
                                   <td>" . $x['poids'] . ' kg' .
                              "</td>
                                   <td>" . $s .
                              "</td>
                                   <td>" . $c .
                              "</td>
                                   <td>" . $x['nom'] . ' ' . $x['prenom'] .
                              "</td>
                              </tr></table>";
                    }
               } ?>
               <br>
               <div style="position: relative; padding : 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
                    <center>
                         <br>
                         Ajouter un animal
                         <br>
                         <br>
                         <form method="post" action="?">
                              <br> Durée:
                              <select name="heure">
                                   <option value="0" selected>0</option>
                                   <?php
                                   for ($i = 1; $i <= 24; $i++) {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                   }
                                   ?>
                              </select>
                              h
                              <select name="min">
                                   <option value="0" selected>0</option>
                                   <?php
                                   for ($i = 1; $i <= 59; $i++) {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                   }
                                   ?>
                              </select>
                              min
                              <select name="sec">
                                   <option value="0" selected>0</option>
                                   <?php
                                   for ($i = 1; $i <= 59; $i++) {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                   }
                                   ?>
                              </select>
                              sec

                              <br>
                              <input type="text" name="taille" placeholder="Taille (22.55)" required><br>
                              <input type="text" name="poids" placeholder="Poids (2.55)" required><br>
                              <p>Anamnese</p>
                              <textarea id="story" name="anamnese" rows="3" cols="80"></textarea>
                              <br>
                              <p>Diagnostique</p>
                              <textarea id="story" name="diagnostic" rows="3" cols="80"></textarea>
                              <br>
                              <p>Manipulations</p>
                              <textarea id="story" name="manip" rows="3" cols="80"></textarea>
                              <br>
                              <select name="suivi">
                                   <option value="" selected> non </option>
                              </select>
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
                         /*
                         while ($x = $tableAnimal->fetch()) {
                              $s = ($x['sexe'] == "f") ? 'Femelle' : 'Mâle';
                              $c = ($x['castration'] == "o") ? 'oui' : 'non';

                              echo "<tr><td>" . $x['nomAnimal'] .
                                   "</td><td>" . $x['espece'] .
                                   "</td><td>" . $x['race'] .
                                   "</td><td>" . $x['taille'] . ' cm' .
                                   "</td><td>" . $x['poids'] . ' kg' .
                                   "</td><td>" . $s .
                                   "</td><td>" . $c .
                                   "</td><td>" . $x['nom'] . ' ' . $x['prenom'] .
                                   "</td><td>" . ' ' . '<form method="post" action="./animaux.php">
                                                            <input type="hidden" name="idProp" value="' . $x['idProprietaire'] . '" >
                                                            <input type="submit" name="majAnimal" value="modifier">
                                                            <input type="submit" name="delAnimal" value="supprimer">
                                                       </form>' .
                                   "</td></tr>";
                         }*/
                         ?>
                    </table>
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