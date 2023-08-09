<?php
/* The above code is a PHP script that displays a table of events from a database. It first checks if
the user is logged in, and if not, redirects them to a login page. It then connects to the database
and retrieves events based on various filters provided by the user through a form. The events are
displayed in a table with columns for the event number, name, date, description, client, manager,
event planner, DJ, theme, type, location, rental fee, and playlist. The user can also log out using
a separate form. */

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
$nom_filtrer = isset($_POST['nom_filtrer']) ? $_POST['nom_filtrer'] : '';
$client_filtrer = isset($_POST['client_filtrer']) ? $_POST['client_filtrer'] : '';
$manager_filtrer = isset($_POST['manager_filtrer']) ? $_POST['manager_filtrer'] : '';
$plannificateur_filtrer = isset($_POST['plannificateur_filtrer']) ? $_POST['plannificateur_filtrer'] : '';
$dj_filtrer = isset($_POST['dj_filtrer']) ? $_POST['dj_filtrer'] : '';
$theme_filtrer = isset($_POST['theme_filtrer']) ? $_POST['theme_filtrer'] : '';
$type_filtrer = isset($_POST['type_filtrer']) ? $_POST['type_filtrer'] : '';
$localisation_filtrer = isset($_POST['localisation_filtrer']) ? $_POST['localisation_filtrer'] : '';
$frais_filtrer = isset($_POST['frais_filtrer']) ? $_POST['frais_filtrer'] : '';
$playlist_filtrer = isset($_POST['playlist_filtrer']) ? $_POST['playlist_filtrer'] : '';

// Récupération des events de la base de données
$query = 'SELECT * FROM EVENT WHERE 1=1';
$parameters = array();

if (!empty($date_filtrer)) {
    $query .= " AND DATE = :date";
    $parameters[':date'] = $date_filtrer;
}

if (!empty($numero_filtrer)) {
    $query .= " AND ID = :numero";
    $parameters[':numero'] = $numero_filtrer;
}

if (!empty($nom_filtrer)) {
    $query .= " AND NAME LIKE :nom";
    $parameters[':nom'] = '%' . $nom_filtrer . '%';
}

if (!empty($client_filtrer)) {
    $query .= " AND CLIENT = :client";
    $parameters[':client'] = $client_filtrer;
}

if (!empty($manager_filtrer)) {
    $query .= " AND MANAGER = :manager";
    $parameters[':manager'] = $manager_filtrer;
}

if (!empty($plannificateur_filtrer)) {
    $query .= " AND EVENT_PLANNER = :plannificateur";
    $parameters[':plannificateur'] = $plannificateur_filtrer;
}

if (!empty($dj_filtrer)) {
    $query .= " AND DJ = :dj";
    $parameters[':dj'] = $dj_filtrer;
}

if (!empty($theme_filtrer)) {
    $query .= " AND THEME LIKE :theme";
    $parameters[':theme'] = '%' . $theme_filtrer . '%';
}

if (!empty($type_filtrer)) {
    $query .= " AND TYPE LIKE :type";
    $parameters[':type'] = '%' . $type_filtrer . '%';
}

if (!empty($localisation_filtrer)) {
    $query .= " AND LOCATION = :localisation";
    $parameters[':localisation'] = $localisation_filtrer;
}

if (!empty($frais_filtrer)) {
    $query .= " AND RENTAL_FEE = :frais";
    $parameters[':frais'] = $frais_filtrer;
}

if (!empty($playlist_filtrer)) {
    $query .= " AND PLAYLIST LIKE :playlist";
    $parameters[':playlist'] = '%' . $playlist_filtrer . '%';
}

$stmt = $bdd->prepare($query);
$stmt->execute($parameters);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html>
    <head>
        <title>Events</title>
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
        <h1>Events</h1>

        <!-- Formulaire de recherche -->
    <form method="post" action="event.php">
        <label for="date_filtrer">Filtrer par date :</label>
        <input type="text" name="date_filtrer" value="<?= $date_filtrer ?>">
        <br>
        <label for="numero_filtrer">Filtrer par numéro :</label>
        <input type="text" name="numero_filtrer" value="<?= $numero_filtrer ?>">
        <br>
        <label for="nom_filtrer">Filtrer par nom :</label>
        <input type="text" name="nom_filtrer" value="<?= $nom_filtrer ?>">
        <br>
        <label for="client_filtrer">Filtrer par client :</label>
        <input type="text" name="client_filtrer" value="<?= $client_filtrer ?>">
        <br>
        <label for="manager_filtrer">Filtrer par manager :</label>
        <input type="text" name="manager_filtrer" value="<?= $manager_filtrer ?>">
        <br>
        <label for="plannificateur_filtrer">Filtrer par planificateur d'événement :</label>
        <input type="text" name="plannificateur_filtrer" value="<?= $plannificateur_filtrer ?>">
        <br>
        <label for="dj_filtrer">Filtrer par DJ :</label>
        <input type="text" name="dj_filtrer" value="<?= $dj_filtrer ?>">
        <br>
        <label for="theme_filtrer">Filtrer par thème :</label>
        <input type="text" name="theme_filtrer" value="<?= $theme_filtrer ?>">
        <br>
        <label for="type_filtrer">Filtrer par type :</label>
        <input type="text" name="type_filtrer" value="<?= $type_filtrer ?>">
        <br>
        <label for="localisation_filtrer">Filtrer par localisation :</label>
        <input type="text" name="localisation_filtrer" value="<?= $localisation_filtrer ?>">
        <br>
        <label for="frais_filtrer">Filtrer par frais :</label>
        <input type="text" name="frais_filtrer" value="<?= $frais_filtrer ?>">
        <br>
        <label for="playlist_filtrer">Filtrer par playlist :</label>
        <input type="text" name="playlist_filtrer" value="<?= $playlist_filtrer ?>">
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
                <?php foreach ($events as $event): ?>
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
                    <input type="submit" value="Déconnexion">
                </p>
            </form>
    </body>
</html>
