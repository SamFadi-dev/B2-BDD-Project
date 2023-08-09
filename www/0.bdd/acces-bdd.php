<?php
/* The code provided is a PHP script that generates an HTML page where the admin can acces de db. */

//-------------------------------------------------
//-----------CODE PRINCIPALE QUESTION 0------------
//-------------------------------------------------
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>0. Base de données</title>
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
    <div class="container">
        <?php
        echo '<h1>Choisissez la table à accéder </h1>';
        echo '<ul>';
        echo '<li><a href="clients.php" target="_blank">Clients</a></li>';
        echo '<li><a href="employee.php" target="_blank">Employees</a></li>';
        echo '<li><a href="location.php" target="_blank">Locations</a></li>';
        echo '<li><a href="event.php" target="_blank">Events</a></li>';
        echo '<li><a href="song.php" target="_blank">Songs</a></li>';
        echo '<li><a href="cd.php" target="_blank">CDs</a></li>';
        echo '</ul>';
        ?>
    </div>
    </body>
</html>