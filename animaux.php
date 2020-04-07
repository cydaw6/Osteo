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
            </style>
</head>
<body>          

              <div style="height: 158px;max-width: 100%;position: relative;z-index: 3;">
                
                   <?php
                    include './includes/header.php';

                    include './includes/right-navbar.php';
                    include './includes/database.php';
                    $result=$db->query("SELECT * FROM ANIMAL");
                    ?>
                
            </div>
           <br /><br />

          
            
                                

                <div style="padding-left: 170px;margin-right: 0px;margin-left: 0px;background-color:white;max-width: 100%;">
                    
                           <div class="container" style="background-color: white;max-width: 830px;min-width: 100px!important;">  
                                <h3 align="center"> Animaux </h3>  
                                <br />  
                                <div class="table-responsive">  
                                     <table id="employee_data" class="table table-striped table-bordered">  
                                          <thead>  
                                               <tr>  
                                                <th>id</th>
                                <th>Nom</th>
                                <th>Espèce</th>
                                <th>Race</th>
                                <th>Taille</th>
                                <th>Poids</th>
                                <th>Genre</th>
                                <th>Castration</th>
                                <th>id Propriétaire</th>  
                                               </tr>  
                                          </thead>  
                                          <?php  
                                         while($t = $result->fetch())  
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
                                               ;
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



