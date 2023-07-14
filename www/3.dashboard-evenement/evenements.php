<?php
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
            position: relative;
            min-height: 100vh;
            margin: 0;
            padding-bottom: 0px;
            font-size: 18px;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 32px;
            text-align: center;
            margin-top: 0;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin-top: 30px;
        }

        li {
            margin-bottom: 10px;
        }

        li a {
            display: block;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #dddddd;
            border-radius: 5px;
            text-decoration: none;
            color: #333333;
            transition: background-color 0.3s;
        }

        li a:hover {
            background-color: #eeeeee;
        }

        .image-bas-droite {
            position: fixed;
            bottom: 0;
            right: 0;
            transform: translate(20px, -100px);
            width: 250px;
        }

        .login-form {
            margin-top: 30px;
            text-align: center;
        }

        .login-form input {
            padding: 10px;
            margin-right: 10px;
        }

        .login-form button {
            padding: 10px 20px;
            background-color: #333333;
            border: none;
            border-radius: 5px;
            color: #ffffff;
            cursor: pointer;
        }

        .login-form button:hover {
            background-color: #555555;
        }
    </style>
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
            $query = 'SELECT *, 
            CASE
                WHEN DATE < DATE(NOW()) THEN "PASSÉ"
                WHEN DATE > DATE(NOW()) THEN "FUTUR"
                ELSE "AUJOURD\'HUI"
            END AS STATUT,
            (1500 + COALESCE(RENTAL_FEE, 0)) AS TOTALCOUNT
            FROM EVENT
            ORDER BY DATE DESC, NAME ASC';
        

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