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
              .linktitle
                {
                position: absolute;
                z-index: -1;
                left: -65px!important;
                padding: 28px 14px!important;
                line-height:0px;
                background:#222527;
                }
              }
              form > input{
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
                 $a = $_SESSION['id'];
                 $tableAnimal=$db->query("SELECT * FROM animal WHERE osteo_id=$a");

                 function containsSpecialChars($str){
                    if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬]/', $str))
                    {
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
                         $allProprio = $db->query("SELECT * FROM nom_proprio");
                         $a=$_SESSION['id'];
                         $allAnimaux = $db->query("SELECT * FROM animal NATURAL JOIN nom_proprio WHERE osteo_id=$a");
                    ?>
                                <br>
                              <div style="position= relative; padding = 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
                                        <center>
                                        <br>
                                        Ajouter un animal
                                        <br>
                                        <br>
                                        <form method="post" action="?">
                                             Propriétaire
                                             <select name="organisme" required>
                                                  <option value="null"> Définir un propriétaire</option>
                                                  <?php
                                                       while($t=$allProprio->fetch()){
                                                            echo '<option value="'.$t['idProprietaire'].'">'. $t['nom'] . '-' . $t['prenom'] .'</option>';
                                                       }
                                                  ?>
                                             </select><br> 
                                             <input type="text" name="nomAn" placeholder="nom" required><br>
                                             <input type="text" name="especeAn" placeholder="espece" required><br>
                                             <input type="text" name="raceAn" placeholder="race" required><br>
                                             <select name="sexe">
                                                  <option>Sexe:</option>
                                                  <option value="m">Mâle</option>
                                                  <option value="f">Femelle</option>
                                             </select>
                                             <label for="castration">Castration:</label>
                                             <select name="castration">
                                                  <option value="n">non</option>
                                                  <option value="o">oui</option>
                                             </select><br>
                                             <input type="text" name="taille" placeholder="Taille" required><br>
                                             <input type="text" name="poids" placeholder="Poids" required><br>
                                             <p>Anamnese</p>
                                             <textarea id="story" name="story"rows="5" cols="33"></textarea>
                                             <br>                                            
                                             <input type="submit" name="subProp" value="Ajouter">
                                             <input type="reset" value="Effacer" onAction=>
                                             <br>
                                             <br>
                                        </form>
                                          
                                          <?php
                                               if(isset($_POST['subProp'])){
                                                    extract($_POST);
                                                    if(containsSpecialChars($nomPa) || containsSpecialChars($prenomPa) || 
                                                         containsSpecialChars($telPa) || containsSpecialChars($adresse) || 
                                                         containsSpecialChars($nomPa) || containsSpecialChars($codePostal) || containsSpecialChars($fonction) ){
                                                              echo 'Les caractères spéciaux ne sont pas autorisés';
                                                    
                                                    }elseif (containsNumber($nomPa) || containsNumber($prenomPa) ){
                                                         echo 'Le nom ou le prénom ne peuvent contenir des nombres';
                                                    }elseif (!ctype_digit($telPa)){
                                                         echo 'Le numéron de téléphone ne doit contenir que des chiffres';
                                                    }elseif (!ctype_digit($codePostal)){
                                                         echo 'Le code postal ne doit contenir que des chiffres';
                                                    }elseif(!filter_var($emailPa, FILTER_VALIDATE_EMAIL)){
                                                         echo 'Adresse email non valide';
                                                         $quer=$db->query("SELECT email FROM particulier WHERE email= :email");
                                                         $quer->execute(['email'=>$emailPa]);
                                                         $numb = $quer->rowCount();
                                                         if($numb >= 1){
                                                              echo 'Cette adresse email est déjà utilisée';
                                                         }
                                                    }else{
                                                         $homonyme = $db->prepare("SELECT idProprietaire FROM particulier WHERE nomPa= :nomPa AND prenomPa= :prenomPa AND adresse= :adresse AND localite= :localite");
                                                         $homonyme->execute(['nomPa' => $nomPa, 'prenomPa' => $prenomPa, 'adresse'=>$adresse, 'localite'=> $localite]);
                                                         $result = $homonyme->rowCount();
                                                         if($result>=1) { # verification dans la base générale
                                                              $samePersonID = $homonyme->fetch();
                                                              $homonyme2 = $db->prepare("SELECT * FROM particulier NATURAL JOIN `possede_proprio` WHERE osteo_id=:osteoId AND idProprietaire=:idProp");
                                                              $homonyme2->execute(['osteoId' => $_SESSION['id'], 'idProp'=> $samePersonID['idProprietaire']]);
                                                              $nb = $homonyme2->rowCount();
                                                              
                                                              if($nb>=1){ # verification dans la base unique de l'osteo
                                                                   echo 'Cette personne est déjà enregistrée';

                                                              }else{
                                                                   addPersonalProprio($db,$samePersonID['idProprietaire']);
                                                              }
                                                         }else{
                                                              addParticulier($db);
                                                         }
                                                    }
                                               }else if(isset($_POST['subOrga'])){
                                                    // faire ici les organisations
                                               }

                                          ?>

                                </div>
                                <br>
                         
                           <h3 align="center"> Animaux </h3> 
                           <br />  
                           <div class="table-responsive" style="position=relative;">  
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
                                                  </tr>  
                                                            </thead>  
                                                            <?php  
                                                            while($t = $tableAnimal->fetch())  
                                                            {  
                                                            "<tr><td>".$t['nomAnimal'].
                                                            "</td><td>".$t['espece'].
                                                            "</td><td>".$t['race'].
                                                            "</td><td>".$t['taille'].
                                                            "</td><td>".$t['poids'].
                                                            "</td><td>".$t['sexe'].
                                                            "</td><td>".$t['castration'].
                                                            "</td><td>".$t['nom'].' '.$t['prenom'].
                                                            "</td></tr>";
                                                            }  
                                          ?>  
                              </table>  
                         </div>
                    <div id="bottom-bar"> <!-- logo univ-->
                      <p></p>
                    </div> 
                 </div>
             </div>                               
</body>
</html>

<script>
  $(document).ready(function() {
       $('#employee_data').DataTable( {
            "language": {
                 "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
            }
       });
  } );
</script>




