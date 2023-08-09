<?php
/* The code provided is a PHP script that generates a web page displaying a dashboard for CDs. */

session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>6. Tableau de bord des CDs</title>
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
    echo '<h1>Tableau de bord des CDs</h1>';
    try{
        $bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
        if ($bdd == NULL) {
            die("Problème de connexion");
        }
        $bdd->beginTransaction();

        $query = "SELECT CD.CD_NUMBER, CD.TITLE,
                SEC_TO_TIME(SUM(TIME_TO_SEC(SONG.DURATION))) AS total_temps,
                SEC_TO_TIME(MAX(TIME_TO_SEC(SONG.DURATION))) AS max_temps,
                SEC_TO_TIME(MIN(TIME_TO_SEC(SONG.DURATION))) AS min_temps,
                SEC_TO_TIME(AVG(TIME_TO_SEC(SONG.DURATION))) AS avg_temps,
                COUNT(CONTAINS.PLAYLIST) AS playlist_nbr,
                GROUP_CONCAT(DISTINCT GENRE.NAME SEPARATOR ', ') AS related_genres
            FROM CD
            LEFT JOIN SONG ON CD.CD_NUMBER = SONG.CD_NUMBER
            LEFT JOIN CONTAINS ON CD.CD_NUMBER = CONTAINS.CD_NUMBER
            LEFT JOIN GENRE ON SONG.GENRE = GENRE.NAME
            GROUP BY CD.CD_NUMBER, CD.TITLE";


        $stmt = $bdd->prepare($query);
        $stmt->execute();
        $CDs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Affichage de la table
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Titre du CD</th>';
        echo '<th>Durée totale</th>';
        echo '<th>Durée maximale</th>';
        echo '<th>Durée minimale</th>';
        echo '<th>Durée moyenne</th>';
        echo '<th>Apparition</th>';
        echo '<th>Genres/Sous-Genres</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($CDs as $CD) {
            echo '<tr>';
            echo '<td>' . $CD['TITLE'] . '</td>';
            echo '<td>' . $CD['total_temps'] . '</td>';
            echo '<td>' . $CD['max_temps'] . '</td>';
            echo '<td>' . $CD['min_temps'] . '</td>';
            echo '<td>' . $CD['avg_temps'] . '</td>';
            echo '<td>' . $CD['playlist_nbr'] . '</td>';
            echo '<td>' . $CD['related_genres'] . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';

        $bdd->commit();
    } catch (PDOException $e) {
        $bdd->rollback();
        echo "Erreur : " . $e->getMessage();
    }
    ?>
</body>
</html>
