
<head>
	<style type="text/css">

		#box div {
			border-radius: 2px;
			background-color: white;
			box-shadow: 3px 3px 3px #aaaaaa;
		}

		#bod, input[type=password], input[type=submit], input[type=text]{
			max-width: 70%;
		}

	</style>
</head>

<p style="position: relative;z-index: 1;color:white;">___________________________________</p>

                
<div style="background-color: black; height: 2px;"></div>
<div style="background-color: #262626;  background-image: url('./img/tile-profile.png') ; max-width: 700px; height: 25px;"></div>

<div style="background-color:#c8c8c8; background-image: linear-gradient(to top right, #c8c8c8, #f8f8f8);max-width: 700px;"> 
	<br><br>
	<div style="margin-left: 1%;margin-right: 1%;bottom: 0px;height: 50px; max-width: 100%;border-radius: 2px;background-color: white;border: #d5dbdb 1px solid;box-shadow: 3px 3px 3px #aaaaaa;">
		<br>
		<span style="font-size: 20px;font-weight: bold;">
			<?php echo $_SESSION['username'];?>
		</span>                
    </div>

	
	<br>
    <div id ="box" style="width: 100%;">
        <div style="margin-left: 1%;width: 40%; height: 100px; float: left; background: white;margin-right: 5px;"> 
            <b>Infos</b> <br><br>
            <span style="font-size: 13px;">
            <b>Date d'inscription:</b>   <?php echo $_SESSION['date']?>
        	</span>
        </div>

        <div style="margin-right: 1%; margin-left: 50%; height: 100px; background: white ;"> 

        	<b> E-mail </b>
            <br>
            <span style="font-size: 13px;">
            <?php echo $_SESSION['email']?>
        	</span>
        	<br><br>
            <form method="post" action="./index.php">
	            <input type="text" name="new-email" required>
	            <input hidden name="profil">
	            <input type="submit" name="sub-email" value="modifier">
            </form>
           

        </div>
    </div>
    <br>
    <div id ="box" style="width: 100%;">
        
        <div style="max-width: 100%;margin-right: 1%; margin-left: 1%; height: 100px; background: white ;"> 
             <b> Mot de passe </b>
            <br>
            <br>
            <form method="post" action="./index.php">
	            <input type="text" name="new-password" required>
	            <input hidden name="profil">
	            <input type="submit" name="sub-password" value="modifier">
            </form>

        </div>
    </div>
    <br>

    <?php

    if(isset($_POST['sub-password'])){


    	if (strlen($_POST['new-password'])<8){
			echo ' <p style="color: red;">Votre mot de passe doit contenir huits caractères minimum </p';
		}else{
			$c = $db->prepare("SELECT username FROM users WHERE username= :username");
			$c->execute(['username' => $_SESSION['username']]);
			$result = $c->fetch();

			if($result){
				$options = ['cost' => 13,]; // durée du hashage
                $hashpass = password_hash($_POST['new-password'], PASSWORD_BCRYPT, $options);
				$c = $db->prepare("UPDATE users SET password =:password WHERE username= :username");

				$c->execute(['username' => $_SESSION['username'],
							 'password' => $hashpass,
							]);

				if($c){
					echo '<p style="color: green;">Mot de passe modifié</p>';
				}else{
					echo '<p style="color: red;"> La modification du mot de passe à échoué</p>';
				}

				
			}
		}
    }elseif (isset($_POST['sub-email'])) {
    	if (!filter_var($_POST['new-email'], FILTER_VALIDATE_EMAIL)){
            echo '<p style="color: red;"> Entrez une adresse email valide</p>';
   	 	}else{
   	 		$c = $db->prepare("SELECT email FROM users WHERE email= :email");
            $c->execute(['email' => $_POST['new-email']]);
            $result = $c->rowCount();
            if($result == 0){

            	$c = $db->prepare("UPDATE users SET email =:email WHERE username= :username");

				$c->execute(['username' => $_SESSION['username'],
							 'email' => $_POST['new-email'],
							]);

				if($c){
					echo '<p style="color: green;">Email modifié</p>';
				}else{
					echo '<p style="color: red;"> La modification de l\'email à échoué</p>';
				}


            }else{
           	 	echo '<p style="color: red;"> Cet email est déjà utilisé</p>';

            }
        }

   	 	}


    ?>

	


</div>
	