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

              form input[type=text]{
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
                    $a = $_SESSION['id'];
                    $tableParticulier=$db->query("SELECT nomPa,prenomPa,telPa,emailPa,adresse,localite,codePostal FROM particulier NATURAL JOIN possede_proprio WHERE osteo_id=$a");


                    function containsNumber($str){
                         if (preg_match('#[0-9]#',$str)){

                              return true;
                         }
                         return false;
                         

                    }

                    function containsSpecialChars($str){
                         if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬]/', $str))
                         {
                                   return true;
                         }
                         return false;
                    }

                    function particulierAlreadyExists($result){ 
                         while($t = $result->fetch()){
                              if($t['adresse']==$_POST['adresse'] && $t['localite']==$_POST['localite']){
                                   return true;
                              }
                         }
                         return false;
                    }

                    function addParticulier($db){
                         
                         $db -> beginTransaction(); // Opération sécurisée car plusieurs personnes peuvent ajouter des propriétaires !
                         $db -> query("INSERT INTO proprietaire (idProprietaire) VALUES (DEFAULT);");
                         $result = $db -> query("SELECT idProprietaire FROM proprietaire WHERE idProprietaire=LAST_INSERT_ID();"); // On récupère l'id tout juste créé
                         $id = $result->fetch();
                         echo $id['idProprietaire'] . '  '. $_POST['prenomPa'];
                         /* On valide les modifications */
                         $db->commit();

                         $a = (int) $id['idProprietaire'];
                         $b =  $_POST['nomPa'];
                         $c = (string)$_POST['prenomPa'];
                         $d = (int) $_POST['telPa'];
                         $e =  $_POST['emailPa'];
                         $f = $_POST['adresse'];
                         $g = $_POST['localite'];
                         $h = (int) $_POST['codePostal'];
                        
                         $x = $db->prepare("INSERT INTO `particulier` (idProprietaire, nomPa, prenomPa, telPa, emailPa, adresse, localite, codePostal) VALUES (:a, :b, :c, :d, :e, :f, :g, :h)");
                         $x -> execute(array(':a'=>$a, 'b'=>$b, 'c'=>$c, 'd'=>$d, 'e'=>$e, 'f'=>$f, 'g'=>$g, 'h'=>$h));

                         $possedeProprio = $db ->prepare("INSERT INTO `possede_proprio` VALUES(:userID, :idProp) ");
                         $possedeProprio -> execute(['userID' =>  $_SESSION['id'], 'idProp' => $id['idProprietaire']]);

                         ?>
                         <meta http-equiv="refresh" content="0">
                         <?php
                         /*
                         (:a,:b,:c,:d,:e,:f,:g,:h)
                         execute(array(':a'=>$a,':b'=>$b,':c'=>$c,':d'=>$d,':e'=>$e,':f'=>$f,':g'=>$g,':h'=>$h));
                         49,'5','8',5,'1','8','6',7
                         $a,$b,$c,$d,$e,$f,$g,$h
                         VALUES (:idPa, :nomPa, :prenomPa, :telPa, :emailPa; :adresse, :localite, :codePostal)");
                         $addPa->execute(['idPa' => $id['idProprietaire'], 
                                        'nomPa' => $_POST['nomPa'], 
                                        'prenomPa' =>  $_POST['prenomPa'], 
                                        'telPa' =>  $_POST['telPa'],
                                        'emailPa' => $_POST['emailPa'], 
                                        'adresse' => $_POST['adresse'],
                                        'localite' =>  $_POST['localite'], 
                                        'codePostal' =>  $_POST['codePostal']
                                        ]);

                         $possedeProprio = $db ->prepare("INSERT INTO `possede_proprio` VALUES(:userID, :idProp) ");
                         $possedeProprio -> execute(['userID' =>  $_SESSION['id'], 'idProp' => $id['idProprietaire']]);

                         */
                         
                        
                         




                         
                    }


                    ?>
                
            </div>
           <br /><br />

          
            
                                
           
                <div style="padding-left: 170px; margin-right: 0px; margin-left: 0px; background-color:white; max-width: 100%;">
                    
                    <div class="container" style="background-color: white; max-width: 830px; min-width: 100px!important;">
                         <br>
                         <center>
                         <div>
                         <br>
                              <div style="position=relative; padding= 3px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;"> 
                                        <br>
                                        Ajouter un organisme 
                                        <br>
                                        <br>
                                        <form method="post" action="./proprietaires.php">
                                             <input type="text" name="raison" placeholder="Raison sociale" required>
                                             <input type="text" name="typeOrga" placeholder="Type d'organisation" required>
                                             <input type="submit" name="subOrga" value="Ajouter">
                                             <input type="reset" value="Effacer">
                                             <br>
                                             <br>
                                        </form>
                              </div>
                              <br>
                              <div style="position= relative; padding = 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
                                        <br>
                                        Ajouter un particulier
                                        <br>
                                        <br>
                                        <form method="post" action="./proprietaires.php">
                                             <input type="text" name="nomPa" placeholder="Nom" required>
                                             <input type="text" name="prenomPa" placeholder="Prénom" required>
                                             <input type="text" name="telPa" placeholder="Téléphone" required>
                                             <input type="text" name="emailPa" placeholder="Email" required>
                                             <input type="text" name="adresse" placeholder="Adresse" required>
                                             <input type="text" name="localite" placeholder="Localite" required>
                                             <input type="text" name="codePostal" placeholder="Code Postal" required>
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
                                                       containsSpecialChars($nomPa) || containsSpecialChars($codePostal) ){
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
                                                       
                                                       
                                                       
                                                       $homonyme = $db->prepare("SELECT nomPa, prenomPa, adresse, localite FROM particulier WHERE nomPa= :nomPa AND prenomPa= :prenomPa");
                                                       $homonyme->execute(['nomPa' => $nomPa, 'prenomPa' => $prenomPa]);
                                                       $result = $homonyme->rowCount();
                                                       if($result>=1) {
                                                            if(particulierAlreadyExists($homonyme)){
                                                                 echo 'Cette personne est déjà enregistrée';
                                                            }else{
                                                                 addParticulier($db);
                                                            }
                                                       }else{
                                                            addParticulier($db);
                                                       }





                                                      

                                                  }


                                                  
                                                  
                                             }else if(isset($_POST['subOrga'])){

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
                                                  <th>email</th>
                                                  <th>Adresse</th>
                                                  <th>Localite</th>
                                                  <th>codePostal</th>
                                             </tr>  
                                        </thead>  
                                             <?php  
                                                  while($t = $tableParticulier->fetch())  
                                                  {  
                                                       echo "<tr><td>".$t['nomPa'].
                                                            "</td><td>".$t['prenomPa'].
                                                            "</td><td>".$t['telPa'].
                                                            "</td><td>".$t['emailPa'].
                                                            "</td><td>".$t['adresse'].
                                                            "</td><td>".$t['localite'].
                                                            "</td><td>".$t['codePostal'].
                                                            "</td></tr>";
                                                       
                                                  }  
                                             ?>  
                              </table>  
                         </div>
                         <h3 align="center"> Organismes </h3>  
                         <br />  
                         <div class="table-responsive" style="position=relative;">  
                              <table id="employee_data" class="table table-striped table-bordered">  
                                        <thead>  
                                             <tr>  
                                                  <th>Raison sociale</th>
                                                  <th>Type</th>
                                             </tr>  
                                        </thead>  
                                             <?php  
                                                  while($t = $tableParticulier->fetch())  
                                                  {  
                                                       echo "<tr><td>".$t['idAnimal'].
                                                            "</td><td>".$t['nomAnimal'].
                                                            "</td><td>".$t['espece'].
                                                            "</td><td>".$t['race'].
                                                            "</td><td>".$t['taille'].
                                                            "</td><td>".$t['poids'].
                                                            "</td><td>".$t['genre'].
                                                            "</td><td>".$t['castration'].
                                                            "</td><td>".$t['idProprietaire'].
                                                            "</td></tr>";
                                                       
                                                  }  
                                             ?>  
                              </table>  
                         </div> 
                    </div>

                    <div id="bottom-bar"> <!-- logo univ-->
                         <p></p>
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
     } );
     } );
</script>



