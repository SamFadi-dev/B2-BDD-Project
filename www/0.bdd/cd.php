<?php
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header('Location: acces-bdd.php');
    exit();
}

$bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
if ($bdd == NULL)
    echo "Problème de connexion";

// Traitement des filtres de recherche
$titre_filtrer = isset($_POST['titre_filtrer']) ? $_POST['titre_filtrer'] : '';
$numero_filtrer = isset($_POST['numero_filtrer']) ? $_POST['numero_filtrer'] : '';
$producteur_filtrer = isset($_POST['producteur_filtrer']) ? $_POST['producteur_filtrer'] : '';
$annees_filtrer = isset($_POST['annees_filtrer']) ? $_POST['annees_filtrer'] : '';
$copies_filtrer = isset($_POST['copies_filtrer']) ? $_POST['copies_filtrer'] : '';

// Récupération des CDs de la base de données
$query = 'SELECT * FROM CD WHERE 1=1';
$parameters = array();

if (!empty($titre_filtrer)) {
    $query .= " AND TITLE LIKE :titre";
    $parameters[':titre'] = '%' . $titre_filtrer . '%';
}

if (!empty($numero_filtrer)) {
    $query .= " AND CD_NUMBER = :numero";
    $parameters[':numero'] = $numero_filtrer;
}

if (!empty($producteur_filtrer)) {
    $query .= " AND PRODUCER LIKE :producteur";
    $parameters[':producteur'] = '%' . $producteur_filtrer . '%';
}

if (!empty($annees_filtrer)) {
    $query .= " AND YEAR = :annees";
    $parameters[':annees'] = $annees_filtrer;
}

if (!empty($copies_filtrer)) {
    $query .= " AND COPIES = :copies";
    $parameters[':copies'] = $copies_filtrer;
}

$stmt = $bdd->prepare($query);
$stmt->execute($parameters);
$CDs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>CDs</title>
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
<h1>CDs</h1>

<!-- Formulaire de recherche -->
<form method="post" action="cd.php">
    <label for="titre_filtrer">Filtrer par titre :</label>
    <input type="text" name="titre_filtrer" value="<?= $titre_filtrer ?>">
    <br>
    <label for="numero_filtrer">Filtrer par numéro de CD :</label>
    <input type="text" name="numero_filtrer" value="<?= $numero_filtrer ?>">
    <br>
    <label for="producteur_filtrer">Filtrer par producteur :</label>
    <input type="text" name="producteur_filtrer" value="<?= $producteur_filtrer ?>">
    <br>
    <label for="annees_filtrer">Filtrer par années :</label>
    <input type="text" name="annees_filtrer" value="<?= $annees_filtrer ?>">
    <br>
    <label for="copies_filtrer">Filtrer par copies :</label>
    <input type="text" name="copies_filtrer" value="<?= $copies_filtrer ?>">
    <br>
    <input type="submit" value="Rechercher">
</form>

<!-- Tableau des CDs -->
<table>
    <thead>
    <tr>
        <th>Numéro</th>
        <th>Titre</th>
        <th>Producteur</th>
        <th>Années</th>
        <th>Copies</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($CDs as $CD): ?>
        <tr>
            <td><?= htmlentities($CD['CD_NUMBER']) ?></td>
            <td><?= htmlentities($CD['TITLE']) ?></td>
            <td><?= htmlentities($CD['PRODUCER']) ?></td>
            <td><?= htmlentities($CD['YEAR']) ?></td>
            <td><?= htmlentities($CD['COPIES']) ?></td>
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
