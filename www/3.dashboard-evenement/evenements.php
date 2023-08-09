<?php
/* The code provided is a PHP script that generates an HTML table displaying a list of events. */

//-------------------------------------------------
//-----------CODE PRINCIPALE QUESTION 3------------
//-------------------------------------------------
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>3. Gestion des événements</title>
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
            die("Problème de connection");
        ?>

        <h1> Liste des événements </h1>
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
            $query = 'SELECT *, 
            CASE
                WHEN DATE < DATE(NOW()) THEN "PASSÉ"
                WHEN DATE > DATE(NOW()) THEN "FUTUR"
                ELSE "AUJOURD\'HUI"
            END AS STATUT,
            ABS(DATEDIFF(DATE, NOW())) AS DIFF_DATE,
            (1500 + COALESCE(RENTAL_FEE, 0)) AS TOTALCOUNT
            FROM EVENT
            ORDER BY DIFF_DATE, NAME ASC';
        
            $stmt = $bdd->query($query);

            while ($row = $stmt->fetch()) {
            // Affichage de chaque ligne de la table sous forme de ligne de tableau

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
                echo "<td>" . $row['TOTALCOUNT'] . "</td>";
                echo "<td>" . $row['STATUT'] . "</td>";
                echo "</tr>";
            }
            ?>
    
        </tbody>
        </table>   
        
    </body>
</html>