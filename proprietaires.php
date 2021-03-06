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
     <?php
     if (!isset($_SESSION['id'])) {
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
               if (!$_SESSION['isAdmin'] == true) {
                    $tableParticulier = $db->query("SELECT nomPa, prenomPa, telPa, emailPa, adresse, localite, codePostal, idProprietaire FROM particulier NATURAL JOIN proprietaire WHERE osteo_id=$a");
               } else {
                    $tableParticulier = $db->query("SELECT * FROM particulier NATURAL JOIN nom_proprio JOIN users ON nom_proprio.osteo_id=users.osteo_id");
               }
               function containsNumber($str)
               {
                    if (preg_match('#[0-9]#', $str)) {
                         return true;
                    }
                    return false;
               }

               function containsSpecialChars($str)
               {
                    if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬]/', $str)) {
                         return true;
                    }
                    return false;
               }

               function addParticulier($db)
               {
                    $db->beginTransaction(); // Opération sécurisée car plusieurs personnes peuvent ajouter des propriétaires !
                    $a = $_SESSION['id'];
                    $db->query("INSERT INTO proprietaire VALUES(DEFAULT, $a)");
                    $result = $db->query("SELECT idProprietaire FROM proprietaire WHERE idProprietaire=LAST_INSERT_ID();"); // On récupère l'id tout juste créé
                    $id = $result->fetch();
                    /* On valide les modifications */
                    $db->commit();
                    $x = $db->prepare("INSERT INTO `particulier` (idProprietaire, nomPa, prenomPa, telPa, emailPa, adresse, localite, codePostal) VALUES (:a, :b, :c, :d, :e, :f, :g, :h)");
                    $x->execute(array(
                         'a' => $id['idProprietaire'],
                         'b' => $_POST['nomPa'],
                         'c' => $_POST['prenomPa'],
                         'd' => $_POST['telPa'],
                         'e' => $_POST['emailPa'],
                         'f' => $_POST['adresse'],
                         'g' => $_POST['localite'],
                         'h' => $_POST['codePostal']
                    ));

                    /* SI contact d'un organisme on l'ajoute à `a_contacter` */

                    if ($_POST['organisme'] != "null") {
                         $idProprio = $id['idProprietaire'];
                         $idOrga = $_POST['organisme'];
                         $fonction = "";

                         if ($_POST['fonction'] != "") {
                              $fonction = $_POST['fonction'];
                         }
                         // echo $id['idProprietaire'].' '.$_POST['organisme']. ' '.$_POST['fonction'];
                         $ajout = $db->prepare("INSERT INTO a_contacter VALUES(:idProprio, :idOrga, :fonction)");
                         $ajout->execute(['idProprio' => $id['idProprietaire'], 'idOrga' => $_POST['organisme'], 'fonction' => $fonction]);
                    }
                    /* On rafraichi la page */
               ?>
                    <meta http-equiv="refresh" content="0">
               <?php
               }

               function addPersonalProprio($db, $id)
               {
                    $possedeProprio = $db->prepare("INSERT INTO `proprietaire` VALUES(:idProp, :userID) ");
                    $possedeProprio->execute(['userID' =>  $_SESSION['id'], 'idProp' => $id]);
                    /* On rafraichi la page */
               ?>
                    <meta http-equiv="refresh" content="0">
               <?php
               }
               ?>

          </div>
          <br /><br />

          <div style="padding-left: 170px; margin-right: 0px; margin-left: 0px; background-color:white; max-width: 100%;">



               <div class="container" style="background-color: white; max-width: 1060px; min-width: 100px!important;">

                    <br>
                    <br>
                    <center>
                         <form method="post" action="?">
                              <select name="printChoice" onchange="this.form.submit()">
                                   <option value="Affichage">Affichage</option>
                                   <option value="Proprio">Particuliers</option>
                                   <option value="Orga">Organismes</option>
                                   <option hidden> --- -- --- -----ooooooooooooooooooooooooooooooooo o o o o o o o o oo o o-- ---- --- ----- -- -- - </option>
                              </select>
                              <input type="submit" name="print" hidden>
                         </form>
                         <div>
                              <br>

                              <?php
                              if (isset($_POST['printChoice'])) {
                                   if ($_POST['printChoice'] == "Orga") {
                                        $_SESSION['showContentProprio'] = "Orga";
                                   } elseif ($_POST['printChoice'] == "Proprio") {
                                        $_SESSION['showContentProprio'] = "Particulier";
                                   }
                              ?>
                                   <meta http-equiv="refresh" content="0">
                                   <?php
                              }

                              if ($_SESSION['showContentProprio'] == "Orga") {
                                   $a = $_SESSION['id'];
                                   if (!$_SESSION['isAdmin'] == true) {
                                        $tableOrga = $db->query("SELECT idProprietaire, raisonSociale, typeOrga FROM organisme NATURAL JOIN proprietaire WHERE osteo_id=$a");
                                   } else {
                                        $tableOrga = $db->query("SELECT * FROM organisme NATURAL JOIN proprietaire NATURAL JOIN users ");
                                   }

                                   $typeOrga = $db->query("SELECT * FROM type_orga");

                                   if (!$_SESSION['isAdmin'] == true) {
                                   ?>

                                        <div style="position:relative; padding: 3px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
                                             <br>
                                             Ajouter un organisme
                                             <br>
                                             <br>
                                             <form method="post" action="?">
                                                  <input type="text" name="raison" placeholder="Raison sociale" required>
                                                  <select name="typeOrga" required>
                                                       <option value="">None</option>
                                                       <?php
                                                       while ($t = $typeOrga->fetch()) {
                                                            echo '<option value="' . $t['typeOrga'] . '">' . $t['typeOrga'] . ' </option>';
                                                       }
                                                       ?>
                                                  </select>
                                                  <!-- on fait en sorte que ce soit la page des organisations qui s'affiche au refresh -->
                                                  <input hidden name="printChoice" value="Orga">
                                                  <input hidden name="print">
                                                  <input type="submit" name="subOrga" value="Ajouter">
                                                  <br>
                                                  <br>
                                             </form>
                                        <?php

                                   }
                                        ?>
                                        </div>
                                        <?php
                                        if (isset($_POST['subOrga'])) {
                                             if (containsSpecialChars($_POST['raison'])) {
                                                  echo 'Les caractères spéciaux ne sont pas autorisés';
                                             } else {
                                                  /* On verif que l'organisme n'existe pas déjà */
                                                  $alreadyExist = $db->prepare("SELECT raisonSociale FROM organisme NATURAL JOIN proprietaire WHERE raisonSociale=:a AND osteo_id=:b");
                                                  $alreadyExist->execute(['a' => $_POST['raison'], 'b' => $_SESSION['id']]);

                                                  if ($alreadyExist->rowCount() >= 1) {
                                                       echo 'Cette organisme est déjà enregistré';
                                                  } else {
                                                       // il n'existe pas dans ses propres enregistrement, on verif maintenant dans la base générale WHERE raisonSociale=$a LIMIT $b
                                                       $alreadyExist = $db->prepare("SELECT idProprietaire FROM organisme WHERE raisonSociale=:a");
                                                       $alreadyExist->execute(['a' => $_POST['raison']]);
                                                       if ($alreadyExist->rowCount() == 1) {
                                                            $res = $alreadyExist->fetch();
                                                            addPersonalProprio($db, $res['idProprietaire']);
                                                       } else { // sinon on l'ajoute et au général

                                                            $db->beginTransaction(); // Opération sécurisée car plusieurs personnes peuvent ajouter des propriétaires !
                                                            $a = $_SESSION['id'];
                                                            $db->query("INSERT INTO proprietaire VALUES(DEFAULT, $a);");
                                                            $result = $db->query("SELECT idProprietaire FROM proprietaire WHERE idProprietaire=LAST_INSERT_ID();"); // On récupère l'id tout juste créé
                                                            $id = $result->fetch();

                                                            /* On valide les modifications */
                                                            $db->commit();
                                                            $op = $db->prepare("INSERT INTO organisme VALUES(:id, :rSoc, :typeO)");
                                                            $res = $op->execute(['id' => $id['idProprietaire'], 'rSoc' => $_POST['raison'], 'typeO' => $_POST['typeOrga']]);
                                                            addPersonalProprio($db, $id['idProprietaire']);
                                                       }
                                                  }
                                             }
                                        }
                                        ?>
                                        <h3 align="center"> Organismes </h3>
                                        <br />
                                        <div class="table-responsive" style="position=relative;">
                                             <table id="employee_data" class="table table-striped table-bordered">
                                                  <thead>
                                                       <tr>
                                                            <th>Raison sociale</th>
                                                            <th>Type</th>
                                                            <?php
                                                            if ($_SESSION['isAdmin'] == true) {
                                                                 echo '<th>Utilisateur</th>';
                                                            }
                                                            ?>
                                                            <th>Action</th>
                                                       </tr>
                                                  </thead>
                                                  <?php
                                                  while ($t = $tableOrga->fetch()) {
                                                       echo "<tr><td>" . $t['raisonSociale'] .
                                                            "</td><td>" . $t['typeOrga'];
                                                       if ($_SESSION['isAdmin'] == true) {
                                                            echo "</td><td>" . $t['username'];
                                                       }


                                                       echo "</td><td>" . ' ' . '<form method="post" action="?">
                                                                                               <input type="hidden" name="idOrga" value="' . $t['idProprietaire'] . '" >
                                                                                               <input type="submit" name="seeOrga" value="Voir les contacts" style="background-color:#4f8196!important;border:hidden;">
                                                                                               <input hidden name="printChoice" value="Orga">
                                                                                               <input hidden name="print">
                                                                                               <input type="submit" name="delOrga" value="supprimer" style="background-color:red!important;border:hidden;">
                                                                                               </form>
                                                                                               <form method="post" action="./liste-animaux.php" target="_blank">
                                                                                                    <input type="hidden" name="idProp" value="' . $t['idProprietaire'] . '" >
                                                                                                    <input type="submit" name="seeAnimals" value="animaux" style="background-color:#834fa8!important;border:hidden;">
                                                                                               </form> 
                                                                                               ' .
                                                            "</td></tr>";
                                                  }
                                                  /* On gère la suppression de l'organisme */
                                                  if (isset($_POST['delOrga'])) {
                                                       $s = $_SESSION['id'];
                                                       $o = $_POST['idOrga'];
                                                       $db->query("DELETE FROM `organisme` WHERE idProprietaire=$o ");
                                                       $db->query("DELETE FROM `proprietaire` WHERE osteo_id=$s AND idProprietaire=$o ");
                                                  ?>
                                                       <meta http-equiv="refresh" content="0">
                                                  <?php
                                                       unset($_POST['delOrga']);
                                                  } elseif (isset($_POST['seeOrga'])) {
                                                       // on définie dans une variable de session l'organisme dont on souhaite voire les contacts
                                                       $_SESSION['shownContacts'] = $_POST['idOrga'];
                                                  }
                                                  ?>
                                             </table>
                                             <br><br><br>
                                             <?php
                                             if (isset($_SESSION['shownContacts'])) {

                                                  $chemin = './proprietaires.php';
                                                  $a = $_SESSION['shownContacts'];
                                                  include("./includes/orga-contacts.php");
                                             }
                                             ?>
                                        </div>
                                   <?php
                              }
                              if ($_SESSION['showContentProprio'] == "Particulier" || $_SESSION['showContentProprio'] != "Orga") {
                                   $allOrga = $db->query("SELECT * FROM organisme ORDER BY raisonSociale");
                                   ?>
                                        <br>
                                        <?php
                                        if (!$_SESSION['isAdmin'] == true || isset($_POST['formModifPart']) || isset($_POST['modifProp'])) {

                                        ?>
                                             <div style="position= relative; padding = 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
                                                  <br>
                                                  <?php
                                                  if (isset($_POST['modifProp'])) {
                                                       $idprop = $_POST['idProp'];
                                                       $infoPart = $db->query("SELECT * FROM particulier WHERE idProprietaire=$idprop");
                                                       $t = $infoPart->fetch();
                                                       echo 'Modifier un particulier';
                                                  } else {
                                                       echo 'Ajouter un particulier';
                                                  }
                                                  ?>
                                                  <br>
                                                  <br>
                                                  <form method="post" action="?">

                                                       <input type="text" name="nomPa" placeholder="Nom" <?php if (isset($_POST['modifProp'])) {
                                                                                                              echo 'value="' . $t['nomPa'] . '"';
                                                                                                         } ?> required>
                                                       <input type="text" name="prenomPa" placeholder="Prénom" <?php if (isset($_POST['modifProp'])) {
                                                                                                                        echo 'value="' . $t['prenomPa'] . '"';
                                                                                                                   } ?>required>
                                                       <input type="text" name="telPa" placeholder="Téléphone" <?php if (isset($_POST['modifProp'])) {
                                                                                                                        echo 'value="' . $t['telPa'] . '"';
                                                                                                                   } ?>required>
                                                       <input type="text" name="emailPa" placeholder="Email" <?php if (isset($_POST['modifProp'])) {
                                                                                                                   echo 'value="' . $t['emailPa'] . '"';
                                                                                                              } ?>required>
                                                       <input type="text" name="adresse" placeholder="Adresse" <?php if (isset($_POST['modifProp'])) {
                                                                                                                        echo 'value="' . $t['adresse'] . '"';
                                                                                                                   } ?>required>
                                                       <input type="text" name="localite" placeholder="Localite" <?php if (isset($_POST['modifProp'])) {
                                                                                                                        echo 'value="' . $t['localite'] . '"';
                                                                                                                   } ?>required>
                                                       <input type="text" name="codePostal" placeholder="Code Postal" <?php if (isset($_POST['modifProp'])) {
                                                                                                                             echo 'value="' . $t['codePostal'] . '"';
                                                                                                                        } ?>required>
                                                       <br>
                                                       <br>
                                                       <?php
                                                       if (!isset($_POST['modifProp'])) {
                                                       ?>
                                                            Si contact d'un organisme
                                                            <select name="organisme">
                                                                 <option value="null"> Organisme</option>
                                                                 <?php
                                                                 while ($t = $allOrga->fetch()) {
                                                                      echo '<option value="' . $t['idProprietaire'] . '">' . $t['raisonSociale'] . '-' . $t['typeOrga'] . '</option>';
                                                                 }
                                                                 ?>
                                                            </select>
                                                            <input type="text" name="fonction" placeholder="Fonction">
                                                       <?php
                                                       }
                                                       ?>
                                                       <br>
                                                       <?php
                                                       if (isset($_POST['modifProp'])) {
                                                            echo '<input hidden name="idPart" value="' . $_POST['idProp'] . '">';
                                                            echo '<input type="submit" name="formModifPart" value="Modifier">  ';
                                                            echo '<input type="submit" value="Annuler">';
                                                       } else {
                                                            echo '<br><br>';
                                                            echo '<input type="submit" name="subProp" value="Ajouter">
                                                  <input type="reset" value="Effacer">';
                                                       }
                                                       ?>
                                                       <br>
                                                       <br>
                                                  </form>

                                                  <?php
                                                  if (isset($_POST['subProp']) || isset($_POST['formModifPart'])) {
                                                       extract($_POST);
                                                       if (
                                                            containsSpecialChars($nomPa) || containsSpecialChars($prenomPa) ||
                                                            containsSpecialChars($telPa) || containsSpecialChars($adresse) ||
                                                            containsSpecialChars($nomPa) || containsSpecialChars($codePostal)
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
                                                       } else {
                                                            if (isset($_POST['formModifPart'])) {
                                                                 $q = $db->prepare("SELECT * FROM particulier NATURAL JOIN proprietaire WHERE osteo_id=:a AND emailPa=:mail AND idProprietaire!=$idPart");
                                                                 $q->execute(['mail' => $emailPa, 'a' => $_SESSION['id']]);
                                                            } else {
                                                                 $q = $db->prepare("SELECT * FROM particulier NATURAL JOIN proprietaire WHERE osteo_id=:a AND emailPa=:mail");
                                                                 $q->execute(['mail' => $emailPa, 'a' => $_SESSION['id']]);
                                                            }

                                                            $numb = $q->rowCount();
                                                            if ($numb != 0) {
                                                                 echo 'Cette adresse email est déjà utilisée';
                                                            } else {
                                                                 if (isset($_POST['formModifPart'])) {
                                                                      $homonyme = $db->prepare("SELECT idProprietaire FROM particulier NATURAL JOIN proprietaire WHERE osteo_id=:ostId AND nomPa= :nomPa AND prenomPa= :prenomPa AND adresse= :adresse AND localite= :localite AND idProprietaire!=$idPart");
                                                                      $homonyme->execute(['nomPa' => $nomPa, 'prenomPa' => $prenomPa, 'adresse' => $adresse, 'localite' => $localite, 'ostId' => $_SESSION['id']]);
                                                                 } else {
                                                                      $homonyme = $db->prepare("SELECT idProprietaire FROM particulier NATURAL JOIN proprietaire WHERE osteo_id=:ostId AND nomPa= :nomPa AND prenomPa= :prenomPa AND adresse= :adresse AND localite= :localite");
                                                                      $homonyme->execute(['nomPa' => $nomPa, 'prenomPa' => $prenomPa, 'adresse' => $adresse, 'localite' => $localite, 'ostId' => $_SESSION['id']]);
                                                                 }
                                                                 $result = $homonyme->rowCount();
                                                                 if ($result >= 1) {
                                                                      echo 'Cette personne est déjà enregistrée';
                                                                 } else {
                                                                      if (isset($_POST['formModifPart'])) {
                                                                           echo '<h1>ouaiii</h1>';
                                                                           $i = $_POST['idPart'];
                                                                           $prep = $db->prepare("UPDATE particulier SET nomPa=:nomPa, prenomPa=:prenomPa, telPa=:telPa, emailPa=:emailPa, adresse=:adresse, localite=:localite, codePostal=:codePostal WHERE idProprietaire=:i");
                                                                           $prep->execute([
                                                                                'nomPa' => $nomPa,
                                                                                'prenomPa' => $prenomPa,
                                                                                'telPa' => $telPa, 'emailPa' => $emailPa, 'adresse' => $adresse, 'localite' => $localite, 'codePostal' => $codePostal,
                                                                                'i' => $i
                                                                           ]);
                                                  ?>
                                                                           <meta http-equiv="refresh" content="0">
                                                  <?php
                                                                      } else {
                                                                           addParticulier($db);
                                                                      }
                                                                 }
                                                            }
                                                       }
                                                  }
                                                  ?>
                                             </div>
                                             <br>
                                        <?php
                                        }
                                        ?>
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
                                                  <?php
                                                  if ($_SESSION['isAdmin'] == true) {
                                                       echo '<th>Utilisateur</th>';
                                                  }
                                                  ?>
                                                  <th>Action</th>
                                             </tr>
                                        </thead>
                                        <?php
                                        while ($t = $tableParticulier->fetch()) {
                                             echo "<tr><td>" . $t['nomPa'] .
                                                  "</td><td>" . $t['prenomPa'] .
                                                  "</td><td>" . $t['telPa'] .
                                                  "</td><td>" . $t['emailPa'] .
                                                  "</td><td>" . $t['adresse'] .
                                                  "</td><td>" . $t['localite'] .
                                                  "</td><td>" . $t['codePostal'];
                                             if ($_SESSION['isAdmin'] == true) {
                                                  echo "</td><td>" . $t['username'];
                                             }
                                             echo "</td><td>" . ' ' . '<form method="post" action="?">
                                                                      <input type="hidden" name="idProp" value="' . $t['idProprietaire'] . '" >
                                                                      <input type="submit" name="modifProp" value="modifier" style="background-color:#4f8196!important;border:hidden;">
                                                                      <input type="submit" name="delProp" value="supprimer" style="background-color:red!important;border:hidden;">  
                                                                 </form>

                                                                 <form method="post" action="./liste-animaux.php" target="_blank">
                                                                      <input type="hidden" name="idProp" value="' . $t['idProprietaire'] . '" >
                                                                      <input type="submit" name="seeAnimals" value="animaux" style="background-color:#834fa8!important;border:hidden;">
                                                                 </form> 
                                                                 ' .
                                                  "</td></tr>";
                                        }

                                        /* On gère la suppression du propriétaire */
                                        if (isset($_POST['delProp'])) {
                                             $s = $_SESSION['id'];
                                             $o = $_POST['idProp'];
                                             $db->query("DELETE FROM `a_contacter` WHERE idProprietaire=$o ");
                                             $db->query("DELETE FROM `particulier` WHERE idProprietaire=$o ");
                                             $db->query("DELETE FROM `proprietaire` WHERE osteo_id=$s AND idProprietaire=$o ");
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
                              }
                         ?>




               </div>

               <div id="bottom-bar">
                    <!-- logo univ-->
                    <p></p>
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