<?php
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
$numero_cd_filtrer = isset($_POST['numero_cd_filtrer']) ? $_POST['numero_cd_filtrer'] : '';
$numero_musique_filtrer = isset($_POST['numero_musique_filtrer']) ? $_POST['numero_musique_filtrer'] : '';
$titre_filtrer = isset($_POST['titre_filtrer']) ? $_POST['titre_filtrer'] : '';
$artiste_filtrer = isset($_POST['artiste_filtrer']) ? $_POST['artiste_filtrer'] : '';
$duree_filtrer = isset($_POST['duree_filtrer']) ? $_POST['duree_filtrer'] : '';
$genre_filtrer = isset($_POST['genre_filtrer']) ? $_POST['genre_filtrer'] : '';

// Récupération des sons de la base de données
$query = 'SELECT * FROM SONG WHERE 1=1';
$parameters = array();

if (!empty($numero_cd_filtrer)) {
    $query .= " AND CD_NUMBER = :cd_number";
    $parameters[':cd_number'] = $numero_cd_filtrer;
}

if (!empty($numero_musique_filtrer)) {
    $query .= " AND TRACK_NUMBER = :track_number";
    $parameters[':track_number'] = $numero_musique_filtrer;
}

if (!empty($titre_filtrer)) {
    $query .= " AND TITLE LIKE :titre";
    $parameters[':titre'] = '%' . $titre_filtrer . '%';
}

if (!empty($artiste_filtrer)) {
    $query .= " AND ARTIST LIKE :artiste";
    $parameters[':artiste'] = '%' . $artiste_filtrer . '%';
}

if (!empty($duree_filtrer)) {
    $query .= " AND DURATION = :duree";
    $parameters[':duree'] = $duree_filtrer;
}

if (!empty($genre_filtrer)) {
    $query .= " AND GENRE LIKE :genre";
    $parameters[':genre'] = '%' . $genre_filtrer . '%';
}

$stmt = $bdd->prepare($query);
$stmt->execute($parameters);
$sons = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Songs</title>
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
        <h1>Sons</h1>

        <!-- Formulaire de recherche -->
        <!-- Formulaire de recherche -->
        <form method="post" action="song.php">
            <label for="numero_cd_filtrer">Filtrer par numéro de CD :</label>
            <input type="text" name="numero_cd_filtrer" value="<?= $numero_cd_filtrer ?>">
            <br>
            <label for="numero_musique_filtrer">Filtrer par numéro de musique :</label>
            <input type="text" name="numero_musique_filtrer" value="<?= $numero_musique_filtrer ?>">
            <br>
            <label for="titre_filtrer">Filtrer par titre :</label>
            <input type="text" name="titre_filtrer" value="<?= $titre_filtrer ?>">
            <br>
            <label for="artiste_filtrer">Filtrer par artiste :</label>
            <input type="text" name="artiste_filtrer" value="<?= $artiste_filtrer ?>">
            <br>
            <label for="duree_filtrer">Filtrer par durée :</label>
            <input type="text" name="duree_filtrer" value="<?= $duree_filtrer ?>">
            <br>
            <label for="genre_filtrer">Filtrer par genre :</label>
            <input type="text" name="genre_filtrer" value="<?= $genre_filtrer ?>">
            <br>
            <input type="submit" value="Rechercher">
        </form>


        <!-- Tableau des sons -->
        <table>
            <thead>
                <tr>
                    <th>Numéro de CD</th>
                    <th>Numéro de musique</th>
                    <th>Titre</th>
                    <th>Artiste</th>
                    <th>Durée</th>
                    <th>Genre</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sons as $son): ?>
                    <tr>
                        <td><?= htmlentities($son['CD_NUMBER']) ?></td>
                        <td><?= htmlentities($son['TRACK_NUMBER']) ?></td>
                        <td><?= htmlentities($son['TITLE']) ?></td>
                        <td><?= htmlentities($son['ARTIST']) ?></td>
                        <td><?= htmlentities($son['DURATION']) ?></td>
                        <td><?= htmlentities($son['GENRE']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Formulaire pour se déconnecter -->
        <form method="post" action="acces-bdd.php">
                <p>
                    <input type="submit" value="Déconnexion">
                </p>
            </form>
    </body>
</html>
