<div style="position: relative; padding : 4px 0px 12px 0px; box-shadow: 3px 3px 3px 3px #aaaaaa;">
    <center>
        <br>
        Associer un traitement pour la dernière consultation ajouté

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


            <input type="submit" name="subTraitement" value="Ajouter">
            <input type="reset" value="Effacer" onAction=>
            <br>
            <br>
        </form>
        <?php
        if (isset($_POST['subTraitement'])) {
            $b = $_SESSION['idLastConsultation'];
            $c = $_POST['idMedicament'];
            $verif = $db->query("SELECT * FROM traitement WHERE idConsultation=$b AND idMedicament=$c");
            if ($verif->rowCount() >= 1) { # verification dans la base unique de l'osteo
                echo 'Vous avez déjà associer un traitement avec ce médicament';
            } else {
                $createAnimal = $db->prepare("INSERT INTO traitement VALUES(:a, :b, :c, :d, :e, :f, :g)");
                $createAnimal->execute([
                    'a' => $_POST['idMedicament'], 'b' => $_SESSION['idLastConsultation'], // lastId : id de la dernière consultation ajouté, lastIdAnimal pareil, à définir dans la page qui fait l'include
                    'c' => $_SESSION['idLastAnimalConsultation'], 'd' => $_POST['produit'],
                    'e' => $_POST['frequence'], 'f' => $_POST['dose'],
                    'g' => $_POST['dureeTraitement']
                ]);
            }
        }
        ?>

</div>