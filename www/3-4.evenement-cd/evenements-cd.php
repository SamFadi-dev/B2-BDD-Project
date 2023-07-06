<?php
//-------------------------------------------------
//-----------CODE PRINCIPALE QUESTION 3/4----------
//-------------------------------------------------
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>3-4. Gestion des événements</title>
    </head>
    <body>
        <?php
        $bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
        if ($bdd == NULL)
            die("Problème de connection");
        ?>

        <h2> Liste des événements </h2>
        <table>
        <thead>
            <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Date</th>
            <th>Description</th>
            <th>Client</th>
            <th>Manager</th>
            <th>Planificateur</th>
            <th>DJ</th>
            <th>Thème</th>
            <th>Type</th>
            <th>Location</th>
            <th>Frais (€)</th>
            <th>Playlist</th>
            <th>Coût total (€)</th>
            <th>Statut</th>
            </tr>
        </thead>
        <tbody>

            <?php
            // Requête SQL pour récupérer les données de la table EVENT
            $query = 'SELECT * FROM EVENT ORDER BY DATE DESC, NAME ASC';
            $stmt = $bdd->query($query);

            $date = date("Y-m-d");
            $statut = "N/A";

            while ($row = $stmt->fetch()) {
            // Affichage de chaque ligne de la table sous forme de ligne de tableau

                $coutTotal = 1500 + $row['RENTAL_FEE'];
        
                if (strtotime($date) > strtotime($row['DATE'])) 
                    $statut = "PASSÉ";
                elseif (strtotime($date) < strtotime($row['DATE']))
                    $statut = "FUTUR";
                else 
                    $statut = "AUJOURD'HUI";

                echo "<tr>";
                echo "<td>" . $row['ID'] . "</td>";
                echo "<td>" . $row['NAME'] . "</td>";
                echo "<td>" . $row['DATE'] . "</td>";
                echo "<td>" . $row['DESCRIPTION'] . "</td>";
                echo "<td>" . $row['CLIENT'] . "</td>";
                echo "<td>" . $row['MANAGER'] . "</td>";
                echo "<td>" . $row['EVENT_PLANNER'] . "</td>";
                echo "<td>" . $row['DJ'] . "</td>";
                echo "<td>" . $row['THEME'] . "</td>";
                echo "<td>" . $row['TYPE'] . "</td>";
                echo "<td>" . $row['LOCATION'] . "</td>";
                echo "<td>" . $row['RENTAL_FEE'] . "</td>";
                echo "<td>" . $row['PLAYLIST'] . "</td>";
                echo "<td>" . $coutTotal . "</td>";
                echo "<td>" . $statut . "</td>";
                echo "</tr>";
            }
            ?>
    
        </tbody>
        </table>   
        
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