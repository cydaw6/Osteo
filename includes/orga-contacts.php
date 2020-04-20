<html>

<head>
    <style>
        #showContacts {
            width: 100%;
        }

        #showContacts,
        th,
        td {
            border: 1px solid #262626;
            border-collapse: collapse;
        }

        #showContacts,
        th,
        td {
            padding: 5px;
            text-align: left;
        }

        #showContacts tr:nth-child(even) {
            background-color: #262626;
        }

        #showContacts tr:nth-child(odd) {
            background-color: #fff;
        }

        #showContacts th {
            background-color: #262626;
            color: white;
        }
    </style>
</head>

<html>

<body>
    <?php
    $allContacts = $db->prepare("SELECT p.idProprietaire, p.nomPa, p.prenomPa, p.telPa, p.emailPa, p.adresse, p.localite, p.codePostal, ac.fonction 
                                            FROM a_contacter ac NATURAL JOIN particulier p JOIN organisme o ON idProprietaire_ORGANISME = o.idProprietaire 
                                            WHERE idProprietaire_ORGANISME=:a");

    $allContacts->execute(['a' => $a]);

    $result = $db->prepare("SELECT * FROM organisme WHERE idProprietaire=:a");
    $result->execute(['a' => $a]);
    $resul = $result->fetch();
    echo '<h4>Liste des contacts de ' . $resul['raisonSociale'] . '  -  ' . $resul['typeOrga'] . '</h4>';

    $idOrga = $a;
    $a = $_SESSION['id'];
    $allParticuliers = $db->query("SELECT idProprietaire, nomPa, prenomPa, emailPa FROM particulier NATURAL JOIN proprietaire WHERE osteo_id=$a ORDER BY nomPa, prenomPa");
    echo '<form method="post" action="?" >
    <select name="particulier" required>
    <option value="">Ajouter un contact</option>';
    while ($t = $allParticuliers->fetch()) {
        echo '<option value="' . $t['idProprietaire'] . '">' . $t['nomPa'] . ' ' . $t['prenomPa'] . ' | ' . $t['emailPa'] . '</option>';
    }
    echo '</select>
    <input hidden name="orga" value="' . $idOrga . '">
    <input type="text" name="fonction" placeholder="Fonction" required>
    <input type="submit" name="addContact" value="Ajouter">
    </form><br>';

    if (isset($_POST['addContact'])) {
        echo $_POST['particulier'] . ' ' . $_POST['orga'] . ' ' . $_POST['fonction'];
        $prep = $db->prepare("INSERT INTO a_contacter VALUES(:b,:a,:c)");
        $prep->execute(['b' => $_POST['particulier'], 'a' =>  $_POST['orga'], 'c' => $_POST['fonction']]);
    ?>
        <meta http-equiv="refresh" content="0">
    <?php
    }
    ?>
    <div>
        <?php
        echo '<table id="showContacts">
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Tel</th>
                            <th>Email</th>
                            <th>Adresse</th>
                            <th>Localite</th>
                            <th>Code Postal</th>
                            <th>Fonction</th>
                            <th>Action</th>
                        <tr>';
        while ($t = $allContacts->fetch()) {
            echo "<tr><td>" . $t['nomPa'] .
                "</td><td>" . $t['prenomPa'] .
                "</td><td>" . $t['telPa'] .
                "</td><td>" . $t['emailPa'] .
                "</td><td>" . $t['adresse'] .
                "</td><td>" . $t['localite'] .
                "</td><td>" . $t['codePostal'] .
                "</td><td>" . $t['fonction'] .
                "</td><td>" . ' ' . '<form method="post" action="?">
                                        <input hidden name="orga" value="' . $idOrga . '">
                                        <input type="hidden" name="idProp" value="' . $t['idProprietaire'] . '" >
                                        <input type="submit" name="delPropContacts" value="supprimer" style="background-color:red!important;border:hidden;">
                                        </form>
                                        ' .
                "</td></tr>";
        }
        echo '</table>';
        if (isset($_POST['delPropContacts'])) {
            $r = $db->prepare("DELETE FROM a_contacter WHERE idProprietaire=:x AND idProprietaire_ORGANISME=:y");
            $r->execute(['x' => $_POST['idProp'], 'y' => $_POST['orga']]);
        ?>
            <meta http-equiv="refresh" content="0">
        <?php
        }
        ?>
    </div>
</body>

</html>