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
$ville_filtrer = isset($_POST['ville_filtrer']) ? $_POST['ville_filtrer'] : '';
$numero_filtrer = isset($_POST['numero_filtrer']) ? $_POST['numero_filtrer'] : '';

// Récupération des Villes de la base de données
$query = 'SELECT * FROM LOCATION WHERE 1=1';
$parameters = array();

if (!empty($ville_filtrer)) {
    $query .= " AND CITY LIKE :ville";
    $parameters[':ville'] = '%' . $ville_filtrer . '%';
}

if (!empty($numero_filtrer)) {
    $query .= " AND ID LIKE :numero";
    $parameters[':numero'] = '%' . $numero_filtrer . '%';
}

$stmt = $bdd->prepare($query);
$stmt->execute($parameters);
$villes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Villes</title>
    </head>
    <body>
        <h1>Villes</h1>

        <!-- Formulaire de recherche -->
        <form method="post" action="location.php">
            <label for="ville_filtrer">Filtrer par ville :</label>
            <input type="text" name="ville_filtrer" value="<?= $ville_filtrer ?>">
            <br>
            <label for="numero_filtrer">Filtrer par numéro :</label>
            <input type="text" name="numero_filtrer" value="<?= $numero_filtrer ?>">
            <br>
            <input type="submit" value="Rechercher">
        </form>

        <!-- Tableau des Villes -->
        <table>
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Rue</th>
                    <th>Ville</th>
                    <th>Code</th>
                    <th>Pays</th>
                    <th>Commentaire</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ville as $ville): ?>
                    <tr>
                        <td><?= htmlentities($ville['ID']) ?></td>
                        <td><?= htmlentities($ville['STREET']) ?></td>
                        <td><?= htmlentities($ville['CITY']) ?></td>
                        <td><?= htmlentities($ville['POSTAL_CODE']) ?></td>
                        <td><?= htmlentities($ville['COUNTRY']) ?></td>
                        <td><?= htmlentities($ville['COMMENT']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Formulaire pour se déconnecter -->
        <form method="post" action="acces-bdd.php">
                <p>
                    <input type="hidden" name="disconnect" value="yes">
                    <input type="submit" value="Deconnection">
                </p>
            </form>
    </body>
</html>
