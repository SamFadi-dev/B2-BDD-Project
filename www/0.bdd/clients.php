<?php
/* This PHP code is a script that displays a list of clients from a database. It allows the user to
filter the clients based on various criteria such as name, email, and phone number. The script also
includes a form for the user to log out. */

session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header('Location: acces-bdd.php');
    exit();
}

$bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
if ($bdd == NULL)
    echo "Problème de connection";
// Traitement des filtres de recherche
$nom_filter = isset($_POST['nom_filter']) ? $_POST['nom_filter'] : '';
$prenom_filter = isset($_POST['prenom_filter']) ? $_POST['prenom_filter'] : '';
$numero_filtrer = isset($_POST['numero_filtrer']) ? $_POST['numero_filtrer'] : '';
$email_filter = isset($_POST['email_filter']) ? $_POST['email_filter'] : '';
$telephone_filter = isset($_POST['telephone_filter']) ? $_POST['telephone_filter'] : '';

// Récupération des clients de la base de données
$query = 'SELECT * FROM CLIENT WHERE 1=1';
$parameters = array();

if (!empty($nom_filter)) {
    $query .= " AND LAST_NAME LIKE :nom";
    $parameters[':nom'] = '%' . $nom_filter . '%';
}

if (!empty($prenom_filter)) {
    $query .= " AND FIRST_NAME LIKE :prenom";
    $parameters[':prenom'] = '%' . $prenom_filter . '%';
}

if (!empty($numero_filtrer)) {
    $query .= " AND CLIENT_NUMBER = :numero";
    $parameters[':numero'] = $numero_filtrer;
}

if (!empty($email_filter)) {
    $query .= " AND EMAIL_ADDRESS LIKE :email";
    $parameters[':email'] = '%' . $email_filter . '%';
}

if (!empty($telephone_filter)) {
    $query .= " AND PHONE_NUMBER = :telephone";
    $parameters[':telephone'] = $telephone_filter;
}

$stmt = $bdd->prepare($query);
$stmt->execute($parameters);
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Clients</title>
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
        <h1>Clients</h1>

        <!-- Formulaire de recherche -->
        <form method="post" action="clients.php">
            <label for="nom_filter">Filtrer par nom :</label>
            <input type="text" name="nom_filter" value="<?= $nom_filter ?>">
            <br>
            <label for="prenom_filter">Filtrer par prénom :</label>
            <input type="text" name="prenom_filter" value="<?= $prenom_filter ?>">
            <br>
            <label for="numero_filtrer">Filtrer par numéro de client :</label>
            <input type="text" name="numero_filtrer" value="<?= $numero_filtrer ?>">
            <br>
            <label for="email_filter">Filtrer par email :</label>
            <input type="text" name="email_filter" value="<?= $email_filter ?>">
            <br>
            <label for="telephone_filter">Filtrer par téléphone :</label>
            <input type="text" name="telephone_filter" value="<?= $telephone_filter ?>">
            <br>
            <input type="submit" value="Rechercher">
        </form>

        <!-- Tableau des clients -->
        <table>
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlentities($client['CLIENT_NUMBER']) ?></td>
                        <td><?= htmlentities($client['FIRST_NAME']) ?></td>
                        <td><?= htmlentities($client['LAST_NAME']) ?></td>
                        <td><?= htmlentities($client['EMAIL_ADDRESS']) ?></td>
                        <td><?= htmlentities($client['PHONE_NUMBER']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Formulaire pour se déconnecter -->
        <form method="post" action="acces-bdd.php">
                <p>
                    <input type="hidden" name="disconnect" value="yes">
                    <input type="submit" value="Déconnexion">
                </p>
            </form>
    </body>
</html>
