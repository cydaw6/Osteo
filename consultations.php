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

          form input[type=text] {
               max-width: 100%;
          }
     </style>
</head>

<body>

     <div style="height: 158px;max-width: 100%;position: relative;z-index: 3;">

          <?php
          include './includes/header.php';
          include './includes/right-navbar.php';
          include './includes/database.php'; // Connexion à la bdd
          //$a = $_SESSION['id'];
          // $tableParticulier=$db->query("SELECT nomPa, prenomPa, telPa, emailPa, adresse, localite, codePostal, idProprietaire FROM particulier NATURAL JOIN possede_proprio WHERE osteo_id=$a");



          ?>
     </div>
     <br /><br />

     <div style="padding-left: 170px; margin-right: 0px; margin-left: 0px; background-color:white; max-width: 100%;">



          <div class="container" style="background-color: white; max-width: 1060px; min-width: 100px!important;">

               <br>
               <br>
               <center>


                    <br>

                    <?php

                    $allOrga = $db->query("SELECT * FROM organisme ORDER BY raisonSociale");
                    ?>
                    <br>
                    <div style="position= relative; padding = 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
                         <br>
                         Ajouter un particulier
                         <br>
                         <br>
                         <form method="post" action="?">
                              <input type="text" name="nomPa" placeholder="Durée" required>
                              <input type="text" name="prenomPa" placeholder="Anamnese" required>
                              <input type="text" name="telPa" placeholder="diagnostique" required>
                              <input type="text" name="emailPa" placeholder="Manipulations" required>
                              <select name="suivi">
                                   <option value="non"></option>
                                   <option value="oui"></option>
                              </select>
                              <input type="text" name="localite" placeholder="Localite" required>
                              <input type="text" name="codePostal" placeholder="Code Postal" required>
                              <label for="suivi">Suivi:</label>
                              <select name="suivi">
                                   <option value="n">non</option>
                                   <option value="o">oui</option>
                              </select><br>
                              <br>
                              <br>
                              Si contact d'une organisation
                              <select name="organisme">
                                   <option value="null"> Organisme</option>
                                   <?php
                                   while ($t = $allOrga->fetch()) {
                                        echo '<option value="' . $t['idProprietaire'] . '">' . $t['raisonSociale'] . '-' . $t['typeOrga'] . '"</option>';
                                   }
                                   ?>
                              </select>
                              <input type="text" name="fonction" placeholder="Fonction">

                              <br>

                              <input type="submit" name="subProp" value="Ajouter">
                              <input type="reset" value="Effacer" onAction=>
                              <br>
                              <br>
                         </form>

                         <?php
                         if (isset($_POST['subProp'])) {
                              extract($_POST);
                              if (
                                   containsSpecialChars($nomPa) || containsSpecialChars($prenomPa) ||
                                   containsSpecialChars($telPa) || containsSpecialChars($adresse) ||
                                   containsSpecialChars($nomPa) || containsSpecialChars($codePostal) || containsSpecialChars($fonction)
                              ) {
                                   echo 'Les caractères spéciaux ne sont pas autorisés';
                              } elseif (containsNumber($nomPa) || containsNumber($prenomPa)) {
                                   echo 'Le nom ou le prénom ne peuvent contenir des nombres';
                              } elseif (!ctype_digit($telPa)) {
                                   echo 'Le numéron de téléphone ne doit contenir que des chiffres';
                              } elseif (!ctype_digit($codePostal)) {
                                   echo 'Le code postal ne doit contenir que des chiffres';
                              } elseif (!filter_var($emailPa, FILTER_VALIDATE_EMAIL)) {
                                   echo 'Adresse email non valide';
                                   $quer = $db->query("SELECT email FROM particulier WHERE email= :email");
                                   $quer->execute(['email' => $emailPa]);
                                   $numb = $quer->rowCount();
                                   if ($numb >= 1) {
                                        echo 'Cette adresse email est déjà utilisée';
                                   }
                              } else {
                                   $homonyme = $db->prepare("SELECT idProprietaire FROM particulier WHERE nomPa= :nomPa AND prenomPa= :prenomPa AND adresse= :adresse AND localite= :localite");
                                   $homonyme->execute(['nomPa' => $nomPa, 'prenomPa' => $prenomPa, 'adresse' => $adresse, 'localite' => $localite]);
                                   $result = $homonyme->rowCount();
                                   if ($result >= 1) { # verification dans la base générale
                                        $samePersonID = $homonyme->fetch();
                                        $homonyme2 = $db->prepare("SELECT * FROM particulier NATURAL JOIN `possede_proprio` WHERE osteo_id=:osteoId AND idProprietaire=:idProp");
                                        $homonyme2->execute(['osteoId' => $_SESSION['id'], 'idProp' => $samePersonID['idProprietaire']]);
                                        $nb = $homonyme2->rowCount();

                                        if ($nb >= 1) { # verification dans la base unique de l'osteo
                                             echo 'Cette personne est déjà enregistrée';
                                        } else {
                                             addPersonalProprio($db, $samePersonID['idProprietaire']);
                                        }
                                   } else {
                                        addParticulier($db);
                                   }
                              }
                         } else if (isset($_POST['subOrga'])) {
                              // faire ici les organisations
                         }

                         ?>

                    </div>
                    <br>
          </div>
          <center>

               <h3 align="center"> Particuliers </h3>
               <br />
               <div class="table-responsive" style="position=relative;">
                    <table id="employee_data" class="table table-striped table-bordered">
                         <thead>
                              <tr>
                                   <th>Nom</th>
                                   <th>Prénom</th>
                                   <th>Tel</th>
                                   <th>Email</th>
                                   <th>Adresse</th>
                                   <th>Localite</th>
                                   <th>Code Postal</th>
                                   <th>Action</th>
                              </tr>
                         </thead>
                         <?php

                         /*
                                                       "</td><td>".' | '. '<button id ="'. $t['idProprietaire'] .'" >Modifier</button> <button id=" ' . $t['idProprietaire'] .' " onClick="supprProp(this.id)">supprimer</button>'.
                                                       */

                         while ($t = $tableParticulier->fetch()) {
                              echo "<tr><td>" . $t['nomPa'] .
                                   "</td><td>" . $t['prenomPa'] .
                                   "</td><td>" . $t['telPa'] .
                                   "</td><td>" . $t['emailPa'] .
                                   "</td><td>" . $t['adresse'] .
                                   "</td><td>" . $t['localite'] .
                                   "</td><td>" . $t['codePostal'] .
                                   "</td><td>" . ' ' . '<form method="post" action="?">
                                                                                     <input type="hidden" name="idProp" value="' . $t['idProprietaire'] . '" >
                                                                                     <input type="submit" name="majProp" value="modifier">
                                                                                     <input type="submit" name="delProp" value="supprimer">
                                                                                     </form>
                                                                                     ' .
                                   "</td></tr>";
                         }

                         /* On gère la suppression du propriétaire */
                         if (isset($_POST['delProp'])) {
                              $s = $_SESSION['id'];
                              $o = $_POST['idProp'];
                              $db->query("DELETE FROM `possede_proprio` WHERE  osteo_id=$s AND idProprietaire=$o ");
                         ?>
                              <meta http-equiv="refresh" content="0">
                         <?php
                              unset($_POST['delProp']);
                         }
                         ?>
                         </form>
                    </table>

               </div>
               <?php

               ?>




     </div>

     <div id="bottom-bar">
          <!-- logo univ-->
          <p></p>
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