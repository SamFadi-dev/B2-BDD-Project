<?php
//-------------------------------------------------
//-----------CODE PRINCIPALE QUESTION 2------------
//-------------------------------------------------
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>2. Gestion des CDs</title>
    </head>
    <body>
        <?php
        $bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
        if ($bdd == NULL)
            die("Problème de connection");
            
        $query = 'SELECT * FROM CD';
        $stmt = $bdd->prepare($query);
        $stmt->execute();
        $cds = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <h1> Sélectionnez un CD à modifier </h1>
        <form method="post" action="choix-cd.php">
            <p>
            <label for="cds">Sélectionnez un CD:</label>
            <select name="cds" id="cds">
                <?php foreach ($cds as $cd): ?>
                    <option value="<?= $cd['CD_NUMBER'] ?>"><?= $cd['CD_NUMBER'] ?> - <?= $cd['TITLE'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Envoyer">
            </p>

        </form>
        <?php

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $cd_choisi = $_POST['cds'];
        $query = 'SELECT * FROM SONG WHERE CD_NUMBER = :cd_choisi';
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(':cd_choisi', $cd_choisi);
        $stmt->execute();
        $chansons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $query_genre = "SELECT * FROM GENRE";
        $stmt_genre = $bdd->prepare($query_genre);
        $stmt_genre->execute();
        $all_genre = $stmt_genre->fetchAll(PDO::FETCH_ASSOC);
        //Afficher les sons du CD
        if ($chansons) {
            echo '<h2>Liste de chansons dans le CD:</h2>';
            echo '<ul>';
            foreach ($chansons as $chanson) {
                echo '<li>'. $chanson['TRACK_NUMBER']. '. ' . $chanson['TITLE'] . ' | ' .$chanson['ARTIST'] . ' | ' .$chanson['DURATION'] . ' | ' .$chanson['GENRE'] .'.</li>';
            }
            
            echo '</ul>';
            echo '<h2>Modifier une chanson</h2>';
            echo '<form method="post" action="modifier-chanson.php">';
                echo '<p>';
                echo '<label for="chansons">Sélectionnez une chanson à modifier :</label>';
                echo '<select name="chansons" id="chansons">';
                foreach ($chansons as $chansons) {
                    echo '<option value="'. $chansons['TRACK_NUMBER'] .'">'. $chansons['TRACK_NUMBER'] .' - '. $chansons['TITLE'] .'</option>';
                }
                echo '</select><br>';
                echo '<label for="m-Titre">Titre :</label>';
                echo '<input type="text" name="m-Titre" id="m-Titre" required>';
                echo '<br>';
                echo '<label for="m-Artiste">Artiste :</label>';
                echo '<input type="text" name="m-Artiste" id="m-Artiste" required>';
                echo '<br>';
                echo '<label for="m-Duree">Durée :</label>';
                echo '<input type="time" name="m-Duree" id="m-Duree" required>';
                echo '<br>';
                echo '<label for="m-Genre">Sélectionnez un genre :</label>';
                echo '<select name="m-Genre" id="m-Genre">';
                foreach ($all_genre as $genre) {
                    echo '<option value="'. $genre['NAME'] .'">'. $genre['NAME'] .'</option>';
                }
                echo '</select><br>';    
                echo '<input type="submit" value="Modifier">';
                echo '</p>';
            echo '</form>';
            ?>
            <?php
            echo '<h2>Ajouter une chanson dans le CD</h2>';
            $cd = $_POST['cds'];
            
            echo '<form method="post" action="ajouter-chanson.php">';
            echo '<input type="hidden" name="numero_cd" value="'.$cd.'">';
                echo '<p>';
                echo '<label for="Titre">Titre :</label>';
                echo '<input type="text" name="Titre" id="Titre" required>';
                echo '<br>';
                echo '<label for="Artiste">Artiste :</label>';
                echo '<input type="text" name="Artiste" id="Artiste" required>';
                echo '<br>';
                echo '<label for="Durée">Durée :</label>';
                echo '<input type="time" name="Durée" id="Durée" required>';
                echo '<br>';
                echo '<label for="all_genre">Sélectionnez un genre :</label>';
                echo '<select name="all_genre" id="all_genre">';
                foreach ($all_genre as $genre) {
                    echo '<option value="'. $genre['NAME'] .'">'. $genre['NAME'] .'</option>';
                }
                echo '</select><br>';
                echo '<input type="submit" value="Ajouter">';
                echo '</p>';
            echo '</form>';


            echo '<h2>Supprimer une chanson</h2>';
            $cd = $_POST['cds'];
            $query = 'SELECT * FROM SONG WHERE CD_NUMBER = :cd';
            $stmt = $bdd->prepare($query);
            $stmt->bindParam(':cd', $cd);
            $stmt->execute();
            $sup_chanson = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo '<form method="post" action="supprimer-chanson.php">';
            echo '<input type="hidden" name="numero_cd" value="'.$cd.'">';
                echo '<p>';
                echo '<label for="sup_chanson">Sélectionnez une chanson à supprimer:</label>';
                echo '<select name="sup_chanson" id="sup_chanson">';
                foreach ($sup_chanson as $sup_chanson) {
                    echo '<option value="'. $sup_chanson['TRACK_NUMBER'] .'">'. $sup_chanson['TRACK_NUMBER'] .' - '. $sup_chanson['TITLE'] .'</option>';
                }
                echo '</select><br>';
                echo '<input type="submit" value="Supprimer">';
                echo '</p>';
            echo '</form>';

        }
    }
    ?>
           
        <h2> Liste des CDs </h2>
            <table>
            <thead>
                <tr>
                <th>Numéro du CD</th>
                <th>Titre</th>
                <th>Producteur</th>
                <th>Année</th>
                <th>Copies</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Requête SQL pour récupérer les données de la table LOCATION
                $query = 'SELECT * FROM CD';
                $stmt = $bdd->query($query);
                while ($row = $stmt->fetch()) {
                // Affichage de chaque ligne de la table sous forme de ligne de tableau
                echo "<tr>";
                echo "<td>" . $row['CD_NUMBER'] . "</td>";
                echo "<td>" . $row['TITLE'] . "</td>";
                echo "<td>" . $row['PRODUCER'] . "</td>";
                echo "<td>" . $row['YEAR'] . "</td>";
                echo "<td>" . $row['COPIES'] . "</td>";
                echo "</tr>";
                }
                ?>
            </tbody>
            </table>    
    </body>
</html>