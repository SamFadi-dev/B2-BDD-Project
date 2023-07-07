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
$query = 'SELECT * FROM EVENT WHERE 1=1';
$parameters = array();

if (!empty($date_filtrer)) {
    $query .= " AND DATE LIKE :date";
    $parameters[':date'] = '%' . $date_filtrer . '%';
}

if (!empty($numero_filtrer)) {
    $query .= " AND ID LIKE :numero";
    $parameters[':numero'] = '%' . $numero_filtrer . '%';
}

$stmt = $bdd->prepare($query);
$stmt->execute($parameters);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <td><?= htmlentities($event['ID']) ?></td>
                        <td><?= htmlentities($event['NAME']) ?></td>
                        <td><?= htmlentities($event['DATE']) ?></td>
                        <td><?= htmlentities($event['DESCRIPTION']) ?></td>
                        <td><?= htmlentities($event['CLIENT']) ?></td>
                        <td><?= htmlentities($event['MANAGER']) ?></td>
                        <td><?= htmlentities($event['EVENT_PLANNER']) ?></td>
                        <td><?= htmlentities($event['DJ']) ?></td>
                        <td><?= htmlentities($event['THEME']) ?></td>
                        <td><?= htmlentities($event['TYPE']) ?></td>
                        <td><?= htmlentities($event['LOCATION']) ?></td>
                        <td><?= htmlentities($event['RENTAL_FEE']) ?></td>
                        <td><?= htmlentities($event['PLAYLIST']) ?></td>
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
