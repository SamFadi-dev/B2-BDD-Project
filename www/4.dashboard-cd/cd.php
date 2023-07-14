<?php
//-------------------------------------------------
//-----------CODE PRINCIPAL QUESTION 4------------
//-------------------------------------------------
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>4. Gestion des CDs</title>
        <style>

        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            color: #333;
        }

        h2 {
            color: #666;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: inline-block;
            width: 120px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 200px;
            padding: 5px;
            margin-bottom: 10px;
        }

        select {
            width: 200px;
            padding: 5px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        input[type="button"] {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .form-separator {
            margin: 20px 0;
            border-top: 1px solid #ddd;
        }
    </style>
    </head>
    <body>
        <?php
        $bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
        if ($bdd == NULL)
            die("Problème de connexion");
        ?>
        
        <h1> Disponibilité des CDs </h1>

        <?php

        // Récupérer les valeurs sélectionnées pour l'attribut et le sens du tri (s'ils existent)
        $selectedAttribute = isset($_GET['attribute']) ? $_GET['attribute'] : 'date';
        $selectedOrder = isset($_GET['order']) ? $_GET['order'] : 'desc';

        // Options disponibles pour l'attribut de tri
        $attributeOptions = [
            'date' => 'Date de l\'événement',
            'title' => 'Titre du CD',
            'copies' => 'Nombre de copies',
        ];

        // Options disponibles pour le sens du tri
        $orderOptions = [
            'desc' => 'Décroissant',
            'asc' => 'Croissant',
        ];

        // Requête SQL avec le tri
        $query = 'SELECT EVENT.DATE, CD.TITLE, CD.COPIES, COUNT(CONTAINS.PLAYLIST) AS copies_utilisees
                FROM EVENT
                LEFT JOIN CONTAINS ON EVENT.PLAYLIST = CONTAINS.PLAYLIST
                LEFT JOIN CD ON CONTAINS.CD_NUMBER = CD.CD_NUMBER
                GROUP BY EVENT.DATE, CD.CD_NUMBER
                ORDER BY ';

        // Ajouter l'attribut et le sens du tri à la requête
        if ($selectedAttribute === 'date') {
            $query .= 'EVENT.DATE';
        } elseif ($selectedAttribute === 'title') {
            $query .= 'CD.TITLE';
        } elseif ($selectedAttribute === 'copies') {
            $query .= 'CD.COPIES';
        }

        $query .= ($selectedOrder === 'desc') ? ' DESC' : ' ASC';

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

        echo '&nbsp; Sens : ';

        echo '<select name="order">';
        foreach ($orderOptions as $value => $label) {
            $selected = ($value === $selectedOrder) ? 'selected' : '';
            echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
        }
        echo '</select>';
        echo '&nbsp';
        echo '<input type="submit" value="Trier">';
        echo '</form>';

        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Date de l\'événement</th>';
        echo '<th>Titre du CD</th>';
        echo '<th>Nombre de copies</th>';
        echo '<th>Copies utilisées</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['DATE'] . '</td>';
            echo '<td>' . $row['TITLE'] . '</td>';
            echo '<td>' . $row['COPIES'] . '</td>';
            echo '<td>' . $row['copies_utilisees'] . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        ?>
    </body>
</html>
