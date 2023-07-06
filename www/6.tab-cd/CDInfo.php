<?php
//-------------------------------------------------
//-----------CODE PRINCIPAL QUESTION 6------------
//-------------------------------------------------
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>6. Tableau de bord des CDs</title>
</head>
<body>
    <?php
    echo '<h1>Tableau de bord des CDs</h1>';
    $bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
    if ($bdd == NULL) {
        die("Problème de connexion");
    }
    // Prend tous les CD
    $query = "SELECT * FROM CD";
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $CDs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prend CONTAINS pour compter le nombre d'apparition des chansons d'un CD dans une playlist
    $query = "SELECT * FROM CONTAINS";
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $playlist = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    // Obtenir chaque CD
    foreach ($CDs as $CD) {
        $CD_NUMBER = $CD['CD_NUMBER'];
        $TITLE = $CD["TITLE"];

        // Requête pour obtenir la durée des chansons
        $query = "SELECT DURATION FROM SONG WHERE CD_NUMBER = :CD_NUMBER";

        $stmt = $bdd->prepare($query);
        $stmt->bindParam(':CD_NUMBER', $CD_NUMBER);
        $stmt->execute();

        // Prendre la durée de toutes les chansons du CD
        $songs = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $max = max($songs);
        $min = min($songs);
        $countSongs = count($songs);
        $totalDuration = 0;
        foreach ($songs as $song) {
            $totalDuration += strtotime($song);
        }
        $average = $totalDuration / $countSongs;

        // Prend toutes les chansons du CD apparaissant dans des playlists.
        $playlistCount = 0;
        // Parcourir CONTAINS et regarder si le CD_NUMBER est égal à celui du CD actuel.
        // Si les CD_NUMBERS sont égaux, incrémenter playlistCount
        foreach ($playlist as $element) {
            if ($element["CD_NUMBER"] == $CD_NUMBER) {
                $playlistCount++;
            }
        }
        $query = "SELECT GROUP_CONCAT(DISTINCT GENRE.NAME SEPARATOR ', ') AS Related_Genres 
                    FROM CD 
                    LEFT JOIN SONG ON CD.CD_NUMBER = SONG.CD_NUMBER 
                    LEFT JOIN CONTAINS ON CD.CD_NUMBER = CONTAINS.CD_NUMBER 
                    LEFT JOIN GENRE ON SONG.GENRE = GENRE.NAME 
                    WHERE CD.CD_NUMBER = :CD_NUMBER
                    GROUP BY CD.CD_NUMBER, CD.TITLE";

        $stmt = $bdd->prepare($query);
        $stmt->bindParam(':CD_NUMBER', $CD_NUMBER);
        $stmt->execute();
        $genre = $stmt->fetch(PDO::FETCH_ASSOC);
        $list = $genre["Related_Genres"];
        echo '<tr>';
        echo '<td>' . $TITLE . '</td>';
        echo '<td>' . date("H:i:s", $totalDuration) . '</td>';
        echo '<td>' . $max . '</td>';
        echo '<td>' . $min . '</td>';
        echo '<td>' . date("H:i:s", $average) . '</td>';
        echo '<td>' . $playlistCount . '</td>';
        echo '<td>' . $list . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    ?>
</body>
</html>