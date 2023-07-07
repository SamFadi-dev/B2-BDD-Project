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
$nom_filter = isset($_POST['nom_filter']) ? $_POST['nom_filter'] : '';
$numero_filtrer = isset($_POST['numero_filtrer']) ? $_POST['numero_filtrer'] : '';

// Récupération des clients de la base de données
$query = 'SELECT * FROM CLIENT WHERE 1=1';
$parameters = array();

if (!empty($nom_filter)) {
    $query .= " AND LAST_NAME LIKE :nom";
    $parameters[':nom'] = '%' . $nom_filter . '%';
}

if (!empty($numero_filtrer)) {
    $query .= " AND CLIENT_NUMBER LIKE :numero";
    $parameters[':numero'] = '%' . $numero_filtrer . '%';
}

$stmt = $bdd->prepare($query);
$stmt->execute($parameters);
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Clients</title>
    </head>
    <body>
        <h1>Clients</h1>

        <!-- Formulaire de recherche -->
        <form method="post" action="clients.php">
            <label for="nom_filter">Filtrer par nom :</label>
            <input type="text" name="nom_filter" value="<?= $nom_filter ?>">
            <br>
            <label for="numero_filtrer">Filtrer par numéro de client :</label>
            <input type="text" name="numero_filtrer" value="<?= $numero_filtrer ?>">
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
                    <input type="submit" value="Deconnection">
                </p>
            </form>
    </body>
</html>
