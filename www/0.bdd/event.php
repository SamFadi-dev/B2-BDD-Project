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
$date_filtrer = isset($_POST['date_filtrer']) ? $_POST['date_filtrer'] : '';
$numero_filtrer = isset($_POST['numero_filtrer']) ? $_POST['numero_filtrer'] : '';

// Récupération des events de la base de données
$query = 'SELECT * FROM EVENT';
if (!empty($date_filtrer)) {
    $query .= " WHERE DATE LIKE '%$date_filtrer%'";
    if (!empty($numero_filtrer)) {
        $query .= " AND ID LIKE '%$numero_filtrer%'";
    }
} elseif (!empty($numero_filtrer)) {
    $query .= " WHERE ID LIKE '%$numero_filtrer%'";
}
$stmt = $bdd->prepare($query);
$stmt->execute();
$event = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Events</title>
    </head>
    <body>
        <h1>Events</h1>

        <!-- Formulaire de recherche -->
        <form method="post" action="event.php">
            <label for="date_filtrer">Filtrer par date :</label>
            <input type="text" name="date_filtrer" value="<?= $date_filtrer ?>">
            <br>
            <label for="numero_filtrer">Filtrer par numéro :</label>
            <input type="text" name="numero_filtrer" value="<?= $numero_filtrer ?>">
            <br>
            <input type="submit" value="Rechercher">
        </form>

        <!-- Tableau des events -->
        <table>
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Nom</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Client</th>
                    <th>Manager</th>
                    <th>Plannificateur d'event</th>
                    <th>DJ</th>
                    <th>Thème</th>
                    <th>Type</th>
                    <th>Localisation</th>
                    <th>Frais</th>
                    <th>PlayList</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($event as $event): ?>
                    <tr>
                        <td><?= $event['ID'] ?></td>
                        <td><?= $event['NAME'] ?></td>
                        <td><?= $event['DATE'] ?></td>
                        <td><?= $event['DESCRIPTION'] ?></td>
                        <td><?= $event['CLIENT'] ?></td>
                        <td><?= $event['MANAGER'] ?></td>
                        <td><?= $event['EVENT_PLANNER'] ?></td>
                        <td><?= $event['DJ'] ?></td>
                        <td><?= $event['THEME'] ?></td>
                        <td><?= $event['TYPE'] ?></td>
                        <td><?= $event['LOCATION'] ?></td>
                        <td><?= $event['RENTAL_FEE'] ?></td>
                        <td><?= $event['PLAYLIST'] ?></td>

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
