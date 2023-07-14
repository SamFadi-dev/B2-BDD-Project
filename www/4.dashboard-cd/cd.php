<?php
//-------------------------------------------------
//-----------CODE PRINCIPALE QUESTION 4------------
//-------------------------------------------------
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>4. Gestion des CDs</title>
    </head>
    <body>
        <?php
        $bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
        if ($bdd == NULL)
            die("Problème de connection");
        ?>
        
        <h2> Disponibilité des CDs </h2>

        <?php

        // Récupérer les valeurs sélectionnées pour l'attribut et le sens du tri (s'ils existent)
        $selectedAttribute = isset($_GET['attribute']) ? $_GET['attribute'] : 'date';
        $selectedOrder = isset($_GET['order']) ? $_GET['order'] : 'desc';

        // Options disponibles pour l'attribut de tri
        $attributeOptions = [
            'title' => 'Titre du CD',
            'copies' => 'Nombre de copies',
            'date' => 'Date de l\'événement',
        ];

        // Options disponibles pour le sens du tri
        $orderOptions = [
            'asc' => 'Croissant',
            'desc' => 'Décroissant',
        ];

        // Requête SQL avec le tri
        $query = 'SELECT CD.TITLE, CD.COPIES, COUNT(*) AS copies_utilisees, EVENT.DATE
                FROM CD
                LEFT JOIN CONTAINS ON CD.CD_NUMBER = CONTAINS.CD_NUMBER
                LEFT JOIN EVENT ON EVENT.PLAYLIST = CONTAINS.PLAYLIST
                GROUP BY CD.CD_NUMBER, EVENT.DATE
                ORDER BY ';

        // Ajouter l'attribut et le sens du tri à la requête
        if ($selectedAttribute === 'title') {
            $query .= 'CD.TITLE';
        } elseif ($selectedAttribute === 'copies') {
            $query .= 'CD.COPIES';
        } else {
            $query .= 'EVENT.DATE';
        }

        $query .= ($selectedOrder === 'asc') ? ' ASC' : ' DESC';

        $stmt = $bdd->prepare($query);
        $stmt->execute();

        // Affichage du tableau avec les listes déroulantes
        echo '<form method="GET">';
        echo 'Trier par : ';
        echo '<select name="attribute">';
        foreach ($attributeOptions as $value => $label) {
            $selected = ($value === $selectedAttribute) ? 'selected' : '';
            echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
        }
        echo '</select>';

        echo ' Sens : ';
        echo '<select name="order">';
        foreach ($orderOptions as $value => $label) {
            $selected = ($value === $selectedOrder) ? 'selected' : '';
            echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
        }
        echo '</select>';

        echo '<input type="submit" value="Trier">';
        echo '</form>';

        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Titre du CD</th>';
        echo '<th>Nombre de copies</th>';
        echo '<th>Copies utilisées</th>';
        echo '<th>Date de l\'événement</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['TITLE'] . '</td>';
            echo '<td>' . $row['COPIES'] . '</td>';
            echo '<td>' . $row['copies_utilisees'] . '</td>';
            echo '<td>' . $row['DATE'] . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';


            ?>
    </body>
</html>