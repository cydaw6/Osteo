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
                         Ajouter une consultation
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
                              <p>Anamnese</p>
                              <textarea id="story" name="anamnese" rows="3" cols="80"></textarea>
                              <br>
                              <p>Diagnostique</p>
                              <textarea id="story" name="diagnostic" rows="3" cols="80"></textarea>
                              <br>
                              <p>Manipulations</p>
                              <textarea id="story" name="manip" rows="3" cols="80"></textarea>
                              <br>
                              <input type="text" name="suivi" placeholder="Suivi"><br>

                              Lieu de la consultation
                              <select name="lieuConsult" required>
                                   <option value="">None</option>
                                   <?php
                                   $a = $_SESSION['id'];
                                   $result = $db->query("SELECT * FROM lieu_consultation WHERE osteo_id=$a ORDER BY lieuConsultation");
                                   while ($t = $result->fetch()) {
                                        echo '<option value="' . $t['lieuConsultation'] . '">' . $t['lieuConsultation'] . '</option>';
                                   }
                                   ?>
                              </select>
                              Type de la consultation
                              <select name="typeConsult" required>
                                   <option value="">None</option>
                                   <?php
                                   $a = $_SESSION['id'];
                                   $result = $db->query("SELECT * FROM type_consultation WHERE osteo_id=$a ORDER BY typeConsultation");
                                   while ($t = $result->fetch()) {
                                        echo '<option value="' . $t['typeConsultation'] . '">' . $t['typeConsultation'] . '</option>';
                                   }
                                   ?>
                              </select>

                              <input type="submit" name="subConsult" value="Ajouter">
                              <input type="reset" value="Effacer" onAction=>
                              <br>
                              <br>
                         </form>
                         <?php
                         if (isset($_POST['subConsult'])) {
                              $anam = (isset($_POST['anamnese'])) ? $_POST['anamnese'] : "-";
                              $diag = (isset($_POST['diagnostic'])) ? $_POST['diagnostic'] : "-";
                              $mani = (isset($_POST['manip'])) ? $_POST['manip'] : "-";
                              $suiv = (isset($_POST['suivi'])) ? $_POST['suivi'] : "-";
                              $dure = $_POST['heure'] . ':' . $_POST['min'] . ':' . $_POST['sec'];
                              $a = $_SESSION['id'];
                              //echo $anam . ' ' . $diag . ' ' . $mani . ' ' . $suiv . ' ' . $dure . ' ' . $_POST['lieuConsult'] . ' ' . $_POST['typeConsult'] . ' ' . $_SESSION['choixAnimalConsult'];


                              $searchTarif = $db->prepare("SELECT idTarif FROM tarif WHERE osteo_id=:a AND lieuConsultation=:b AND typeConsultation=:c");
                              $searchTarif->execute(['a' => $a, 'b' => $_POST['lieuConsult'], 'c' => $_POST['typeConsult']]);

                              if ($searchTarif->rowCount() == 0) {
                                   echo 'Ce tarif n\'existe pas avec ces paramètres';
                              } else {
                                   $resu = $searchTarif->fetch();
                                   $idTarif = $resu['idTarif'];
                                   $createAnimal = $db->prepare("INSERT INTO consultation VALUES(DEFAULT, DEFAULT, :b, :c, :d, :e, :f, :g, :h, :i)");
                                   $createAnimal->execute([
                                        'b' => $dure,
                                        'c' => $anam, 'd' => $diag,
                                        'e' => $mani, 'f' => $suiv,
                                        'g' => $_SESSION['choixAnimalConsult'],
                                        'h' => $idTarif,
                                        'i' => $a
                                   ]);
                              }
                         }
                         ?>
               </div>
               <br>

               <?php
               include './includes/add-traitement.php';
               ?>
               <br>

               <h3 align="center"> Consultations </h3>
               <br />
               <div class="table-responsive" style="position:relative;">
                    <table id="employee_data" class="table table-striped table-bordered">
                         <thead>
                              <tr>
                                   <th>Date</th>
                                   <th>Durée</th>
                                   <th>Animal</th>
                                   <th>Propriétaire</th>
                                   <th>Prix</th>
                                   <th>Action</th>

                              </tr>
                         </thead>
                         <?php
                         $a = $_SESSION['id'];
                         $allConsultations = $db->query("SELECT * FROM `consultation` c 
                                                       JOIN animal 
                                                       JOIN tarif 
                                                       JOIN nom_proprio 
                                                       WHERE animal.idAnimal = c.idAnimal 
                                                            AND tarif.idTarif = c.idTarif 
                                                            AND animal.idProprietaire = nom_proprio.idProprietaire 
                                                            AND c.osteo_id=$a");

                         while ($x = $allConsultations->fetch()) {
                              echo "<tr><td>" . $x['date'] .
                                   "</td><td>" . $x['dureeConsultation'] .
                                   "</td><td>" . $x['nomAnimal'] .
                                   "</td><td>" . $x['nom'] . ' ' . $x['prenom'] .
                                   "</td><td>" . $x['prix'] . ' €' .
                                   "</td><td>" . ' ' . '<form method="post" action="?">
                                                            <input type="hidden" name="idConsultation" value="' . $x['idConsultation'] . '" >
                                                            <input type="submit" name="detail" value="Voir en détail">
                                                            <input type="submit" name="delConsultation" value="supprimer">
                                                       </form>' .
                                   "</td></tr>";
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