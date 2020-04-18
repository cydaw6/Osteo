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
                                                    <input type="hidden" name="idProp" value="' . $t['idProprietaire'] . '" >
                                                    <input type="submit" name="delPropContacts" value="supprimer">
                                                    </form>
                                                    ' .
                "</td></tr>";
        }
        echo '</table>';
        if (isset($_POST['delPropContacts'])) {
            $r = $db->prepare("DELETE FROM a_contacter WHERE idProprietaire=:x");
            $r->execute(['x' => $_POST['idProp']]);
        ?>
            <meta http-equiv="refresh" content="0">
        <?php
        }

        ?>
    </div>
</body>

</html>