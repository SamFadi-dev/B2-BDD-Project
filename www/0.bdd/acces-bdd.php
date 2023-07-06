<?php
//-------------------------------------------------
//-----------CODE PRINCIPALE QUESTION 0------------
//-------------------------------------------------
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>0. Base de données</title>
    </head>
    <body>
        <?php
        echo '<h1>Menu principal</h1>';
        echo '<h2>Choisissez la table à accéder </h2>';
        echo '<ul>';
        echo '<li><a href="clients.php">Clients</a></li>';
        echo '<li><a href="employee.php">Employees</a></li>';
        echo '<li><a href="location.php">Locations</a></li>';
        echo '<li><a href="event.php">Events</a></li>';
        echo '<li><a href="song.php">Songs</a></li>';
        echo '<li><a href="cd.php">CDs</a></li>';
        echo '</ul>';
        ?>
    </body>
</html>