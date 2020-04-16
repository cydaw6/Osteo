<div style="position: relative; padding : 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
    <center>
        <br>
        Associer un traitement

        <form method="post" action="?">
            <select name="idMedicament">
                <option value="0" selected>Pas de médicament</option>
                <?php
                $a = $_SESSION['id'];
                $allMedic = $db->query("SELECT * FROM medicament WHERE osteo_id=$a ORDER BY nomMedicament");
                while ($t = $allMedic->fetch()) {
                    echo '<option value="' . $t['idMedicament'] . '">' . $t['nomMedicament'] . '</option>';
                }

                ?>

            </select><br>
            <input type="text" name="produit" placeholder="Produit"><br>
            <input type="text" name="frequence" placeholder="Fréquence"><br>
            <input type="text" name="dose" placeholder="Dose"><br>
            <input type="text" name="dureeTraitement" placeholder="Durée du Traitement"><br>


            <input type="submit" name="subAn" value="Ajouter">
            <input type="reset" value="Effacer" onAction=>
            <br>
            <br>
        </form>
        <?php
        if (isset($_POST['subAn'])) {
            extract($_POST);


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
            }
        ?>
            <meta http-equiv="refresh" content="0">
        <?php


        }
        ?>

</div>