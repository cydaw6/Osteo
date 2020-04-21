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

                    if (!$_SESSION['isAdmin'] == true || isset($_POST['updateAnimal']) || isset($_POST['majAnimal'])) {
                    ?>
                         <br>

                         <div style="position: relative; padding : 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
                              <center>
                                   <br>
                                   <?php
                                   if (isset($_POST['majAnimal'])) {
                                        echo 'Modifier un animal';
                                        $animalModif = $_POST['idAnimal'];
                                        $y = $db->query("SELECT * FROM animal WHERE idAnimal=$animalModif");
                                        $x = $y->fetch();
                                   } else {
                                        echo 'Ajouter un animal';
                                   }
                                   ?>
                                   <br>
                                   <br>
                                   <form method="post" action="?">
                                        <?php if (!isset($_POST['majAnimal'])) {
                                        ?>
                                             Propriétaire
                                             <select name="idproprio" required>
                                                  <option value=""> None</option>
                                                  <?php
                                                  while ($t = $allProprio->fetch()) {
                                                       echo '<option value="' . $t['idProprietaire'] . '">' . $t['nom'] . '-' . $t['prenom'] . '</option>';
                                                  }
                                                  ?>
                                             </select><br>
                                        <?php
                                        }
                                        ?>
                                        <input type="text" name="nom" placeholder="nom" <?php if (isset($_POST['majAnimal'])) {
                                                                                               echo 'value="' . $x['nomAnimal'] . '"';
                                                                                          } ?> required><br>
                                        <input type="text" name="espece" placeholder="espece" <?php if (isset($_POST['majAnimal'])) {
                                                                                                    echo 'value="' . $x['espece'] . '"';
                                                                                               } ?>required><br>
                                        <input type="text" name="race" placeholder="race" <?php if (isset($_POST['majAnimal'])) {
                                                                                               echo 'value="' . $x['race'] . '"';
                                                                                          } ?>required><br>
                                        Sexe:
                                        <select name="sexe" required>
                                             <?php if (isset($_POST['majAnimal'])) {
                                                  if ($x['sexe'] == "f") {
                                             ?>
                                                       <option value="m">Mâle</option>
                                                       <option value="f" selected>Femelle</option>
                                                  <?php
                                                  } else {
                                                  ?>
                                                       <option value="m" selected>Mâle</option>
                                                       <option value="f">Femelle</option>
                                                  <?php
                                                  }
                                             } else {
                                                  ?>
                                                  <option value="">None</option>
                                                  <option value="m">Mâle</option>
                                                  <option value="f">Femelle</option>

                                             <?php
                                             }

                                             ?>

                                        </select>
                                        Castration:
                                        <select name="castration">
                                             <?php if (isset($_POST['majAnimal'])) {
                                                  if ($x['castration'] == "o") {
                                             ?>
                                                       <option value="n">non</option>
                                                       <option value="o" selected>oui</option>
                                                  <?php
                                                  } else {
                                                  ?>
                                                       <option value="n" selected>non</option>
                                                       <option value="o">oui</option>
                                                  <?php
                                                  }
                                             } else {
                                                  ?>
                                                  <option value="n">non</option>
                                                  <option value="o">oui</option>
                                             <?php
                                             }
                                             ?>

                                        </select><br>
                                        <input type="text" name="taille" placeholder="Taille en cm (22.55)" <?php if (isset($_POST['majAnimal'])) {
                                                                                                                   echo 'value="' . $x['taille'] . '"';
                                                                                                              } ?> required><br>
                                        <input type="text" name="poids" placeholder="Poids en kg (2.55)" <?php if (isset($_POST['majAnimal'])) {
                                                                                                              echo 'value="' . $x['poids'] . '"';
                                                                                                         } ?> required><br>
                                        <p>Anamnese</p>
                                        <textarea id="story" name="anamnese" rows="5" cols="33"><?php if (isset($_POST['majAnimal'])) {
                                                                                                         echo $x['Anamnese'];
                                                                                                    } ?></textarea>
                                        <br>
                                        <?php
                                        if (isset($_POST['majAnimal'])) {
                                             echo ' <input hidden name="idAnimal" value="' . $animalModif . '">';
                                             echo '<input hidden name="idproprio" value="' . $x['idProprietaire'] . '">';
                                        ?>
                                             <input type="submit" name="updateAnimal" value="Modifier">
                                             <input type="submit" value="Annuler">
                                        <?php
                                        } else {
                                        ?>
                                             <input type="submit" name="subAn" value="Ajouter">
                                             <input type="reset" value="Effacer" onAction=>
                                        <?php
                                        }
                                        ?>
                                        <br>
                                        <br>
                                   </form>
                                   <?php
                                   if (isset($_POST['subAn']) || isset($_POST['updateAnimal'])) {
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
                                             if (isset($_POST['updateAnimal'])) {
                                                  $doubleAnimal = $db->prepare("SELECT * FROM animal WHERE nomAnimal= :nom AND espece= :espece AND race= :race AND idProprietaire= :proprioId AND osteo_id= :osteoId AND idAnimal!=:idanimal");
                                                  $doubleAnimal->execute(['nom' => $nom, 'espece' => $espece, 'race' => $race, 'proprioId' => $idproprio, 'osteoId' => $_SESSION['id'], 'idanimal' => $idAnimal]);
                                             } else {
                                                  $doubleAnimal = $db->prepare("SELECT * FROM animal WHERE nomAnimal= :nom AND espece= :espece AND race= :race AND idProprietaire= :proprioId AND osteo_id= :osteoId");
                                                  $doubleAnimal->execute(['nom' => $nom, 'espece' => $espece, 'race' => $race, 'proprioId' => $idproprio, 'osteoId' => $_SESSION['id']]);
                                             }


                                             $result = $doubleAnimal->rowCount();
                                             if ($result >= 1) { # verification dans la base unique de l'osteo
                                                  echo 'Vous avez déjà enregistré cet animal pour ce propriétaire';
                                             } else {
                                                  if (!isset($_POST['updateAnimal'])) {
                                                       $createAnimal = $db->prepare("INSERT INTO animal VALUES(DEFAULT, :a,:b, :c, :d, :e, :f, :g, :h, :i, :j, DEFAULT)");
                                                       $createAnimal->execute([
                                                            'a' => $nom, 'b' => $espece,
                                                            'c' => $race, 'd' => $taille,
                                                            'e' => $poids, 'f' => $sexe,
                                                            'g' => $castration, 'h' => $anamnese,
                                                            'i' => $idproprio, 'j' => $_SESSION['id']
                                                       ]);
                                                  } else {
                                                       $createAnimal = $db->prepare("UPDATE animal SET nomAnimal=:a, espece=:b, race=:c, taille=:d, poids=:e, sexe=:f, castration=:g, Anamnese=:h WHERE idAnimal=:idanimal");
                                                       $createAnimal->execute([
                                                            'a' => $nom, 'b' => $espece,
                                                            'c' => $race, 'd' => $taille,
                                                            'e' => $poids, 'f' => $sexe,
                                                            'g' => $castration, 'h' => $anamnese,
                                                            'idanimal' => $idAnimal
                                                       ]);
                                                  }
                                   ?>
                                                  <meta http-equiv="refresh" content="0">
                                   <?php
                                             }
                                        }
                                   }
                                   ?>
                         </div>
                    <?php
                    }
                    ?>

                    <br>
                    <h3 align="center"> Animaux </h3>
                    <?php
                    $a = $_SESSION['id'];
                    if ($_SESSION['isAdmin'] == true) {
                         $allAnimaux = $db->query("SELECT * FROM animal JOIN nom_proprio ON animal.idProprietaire=nom_proprio.idProprietaire JOIN users ON nom_proprio.osteo_id=users.osteo_id");
                    } else {
                         $allAnimaux = $db->query("SELECT * FROM animal NATURAL JOIN nom_proprio WHERE osteo_id=$a");
                    }
                    ?>
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
                                        <?php
                                        if ($_SESSION['isAdmin']) {
                                             echo '<th>Utilisateur</th>';
                                        }
                                        ?>
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
                                        "</td><td>" . $x['nom'] . '  ' . $x['prenom'];
                                   if ($_SESSION['isAdmin']) {
                                        echo "</td><td>" . $x['username'];
                                   }
                                   echo "</td><td>" . ' ' . '<form method="post" action="./animaux.php">
                                                            <input type="hidden" name="idAnimal" value="' . $x['idAnimal'] . '" >
                                                            <input type="submit" name="seeAnamnese" value="Anamnese" style="background-color:#834fa8!important;border:hidden;">
                                                            <input type="submit" name="majAnimal" value="modifier" style="background-color:#4f8196!important;border:hidden;">
                                                            <input type="submit" name="delAnimal" value="supprimer" style="background-color:red!important;border:hidden;">
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
                         } elseif (isset($_POST['seeAnamnese'])) {
                              $anamnese = $db->query("SELECT * FROM animal NATURAL JOIN nom_proprio NATURAL JOIN users");
                              $r = $anamnese->fetch();
                              echo '<div style="position: relative; max-width:600px; margin-left:2px;padding : 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
                              <h4>Anamnese de l\'animal ' . $r['nomAnimal'] . ' du propriétaire ' . $r['nom'] . ' ' . $r['prenom'] . '</h4>
                              ' . $r['Anamnese'] . '</div><br><br><br>';
                         }
                         ?>
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