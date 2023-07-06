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
$titre_filtrer = isset($_POST['titre_filtrer']) ? $_POST['titre_filtrer'] : '';
$numero_filtrer = isset($_POST['numero_filtrer']) ? $_POST['numero_filtrer'] : '';

// Récupération des CDs de la base de données
$query = 'SELECT * FROM CD';
if (!empty($titre_filtrer)) {
    $query .= " WHERE TITLE LIKE '%$titre_filtrer%'";
    if (!empty($numero_filtrer)) {
        $query .= " AND CD_NUMBER LIKE '%$numero_filtrer%'";
    }
} elseif (!empty($numero_filtrer)) {
    $query .= " WHERE CD_NUMBER LIKE '%$numero_filtrer%'";
}
$stmt = $bdd->prepare($query);
$stmt->execute();
$CDs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>CDs</title>
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
                <?php foreach ($CDs as $CDs): ?>
                    <tr>
                        <td><?= $CDs['CD_NUMBER'] ?></td>
                        <td><?= $CDs['TITLE'] ?></td>
                        <td><?= $CDs['PRODUCER'] ?></td>
                        <td><?= $CDs['YEAR'] ?></td>
                        <td><?= $CDs['COPIES'] ?></td>
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
