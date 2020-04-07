<?php
						// connexion à la base abastos de sqletud

							define('HOST','localhost');
							define('DB_NAME', 'osteo_db');
							define('USER', 'root');
							define('PASS', '');

							try{
								$db = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME, USER, PASS);
								$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						
							}catch(PDOException $e){
								echo "Impossible d'établir une connexion avec la base de données";
							}
					?>