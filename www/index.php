<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>WeND(Y)s Party Management System</title>
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
// Retirer les variables de session si on s'est déconnecté
if (isset($_POST['disconnect'])) {
    session_unset();
}
$bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
if ($bdd == NULL)
    echo "Problème de connection";
if (isset($_POST["login"])) {
    if ($_POST["login"] == "admin" AND $_POST["pass"] == "admin") {
        $_SESSION['login'] = "admin";
    } else
        echo "Votre login/mot de passe est incorrect<br><br>";
}
if (isset($_SESSION['login'])) {
    ?>
    <div class="container">
        <h1>WeND(Y)s Party Management System</h1>
        <ul>
            <li><a href="./0.bdd/acces-bdd.php" target="_blank">0. Accès à la base de données</a></li>
            <li><a href="./1.mod-lieu/mod-lieu.php" target="_blank">1. Modifier une localisation</a></li>
            <li><a href="./2.choix-cd/choix-cd.php" target="_blank">2. Choisir son CD</a></li>
            <li><a href="./3-4.evenement-cd/evenements-cd.php" target="_blank">3/4. Tableau de bord des événements et des CDs</a></li>
            <li><a href="./5.mod-evenement/modifier-event.php" target="_blank">5. Modifier un événement</a></li>
            <li><a href="./6.tab-cd/CDInfo.php" target="_blank">6. Tableau de bord des CDs</a></li>
        </ul>
        <img src="music.png" class="image-bas-droite" alt="Image en bas à droite">
        <form method="post" action="index.php" class="login-form">
            <input type="hidden" name="disconnect" value="yes">
            <button type="submit">Déconnexion</button>
        </form>
    </div>
    <?php
} else {
    ?>
    <div class="container">
        <h1>Veuillez entrer vos identifiants</h1>
        <form method="post" action="index.php" class="login-form">
            <input type="text" name="login" placeholder="Nom d'utilisateur" required>
            <input type="password" name="pass" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
    <?php
}
?>
</body>
</html>
