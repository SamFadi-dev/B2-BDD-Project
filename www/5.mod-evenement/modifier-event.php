<?php
//-------------------------------------------------
//-----------CODE PRINCIPAL QUESTION 5------------
//-------------------------------------------------
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>5. Modifier événement </title>
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
<?php
$bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
if ($bdd == NULL) {
    die("Problème de connexion");
}

try {
    // Requêtes pour récupérer toutes les informations sur les événements
    $query = 'SELECT * FROM EVENT';
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = 'SELECT * FROM MANAGER';
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $managers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = 'SELECT * FROM DJ';
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $djs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = 'SELECT * FROM EVENTPLANNER';
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $planners = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = 'SELECT * FROM LOCATION';
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = 'SELECT * FROM THEME';
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $themes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = 'SELECT * FROM PLAYLIST';
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <h1>Modifier un événement</h1>
    <form method="post" action="modifier-event.php">
        <p>
            <label for="event">Sélectionnez un événement à modifier :</label>
            <select name="event" id="event">
                <?php foreach ($events as $event): ?>
                    <option value="<?= $event["ID"] ?>"><?= $event["ID"] ?> - <?= $event['NAME'] ?></option>
                <?php endforeach; ?>
            </select><br>
            
            <label for="Nom">Nom :</label>
            <input type="text" name="Nom" id="Nom"><br>
            <label for="Description">Description :</label>
            <input type="text" name="Description" id="Description"><br>
            <label for="Manager">Sélectionnez un manager pour l'événement :</label>
            <select name="Manager" id="Manager">
                <?php foreach ($managers as $manager): ?>
                    <option value="<?= $manager["ID"] ?>"><?= $manager["ID"] ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="Planificateur">Sélectionnez un planificateur pour l'événement :</label>
            <select name="Planificateur" id="Planificateur">
                <?php foreach ($planners as $planner): ?>
                    <option value="<?= $planner["ID"] ?>"><?= $planner["ID"] ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="DJ">Sélectionnez un DJ pour l'événement :</label>
            <select name="DJ" id="DJ">
                <?php foreach ($djs as $dj): ?>
                    <option value="<?= $dj["ID"] ?>"><?= $dj["ID"] ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="Theme">Sélectionnez un thème pour l'événement :</label>
            <select name="Theme" id="Theme">
                <?php foreach ($themes as $theme): ?>
                    <option value="<?= $theme["NAME"] ?>"><?= $theme["NAME"] ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="Type">Type d'événement :</label>
            <input type="text" name="Type" id="Type"><br>
            <label for="Location">Sélectionnez un lieu pour l'événement :</label>
            <select name="Location" id="Location">
                <?php foreach ($locations as $location): ?>
                    <option value="<?= $location["ID"] ?>"><?= $location["ID"] ?> - <?= $location['CITY'] ?> -
                        <?= $location['STREET'] ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="Frais">Frais de location :</label>
            <input type="text" name="Frais" id="Frais"><br>
            <label for="Playlist">Sélectionnez une playlist pour l'événement :</label>
            <select name="Playlist" id="Playlist">
                <?php foreach ($playlists as $playlist): ?>
                    <option value="<?= $playlist["NAME"] ?>"><?= $playlist["NAME"] ?></option>
                <?php endforeach; ?>
            </select><br>
            <input type="submit" value="Envoyer">
        </p>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        $bdd->beginTransaction();

        // Obtient les informations actuelles de l'événement à modifier
        $query = "SELECT * FROM EVENT WHERE ID = :ID";
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(":ID", $_POST["event"]);
        $stmt->execute();

        $previousEvent = $stmt->fetch(PDO::FETCH_ASSOC);
        // Puisque les champs ne sont pas obligatoires, on regarde s'ils ont été remplis.
        // Si le champ n'a pas été rempli, on conserve l'ancien, sinon on récupère le nouveau.
        $NAME = !empty($_POST["Nom"]) ? $_POST["Nom"] : $previousEvent["NAME"];
        $DESCRIPTION = !empty($_POST["Description"]) ? $_POST["Description"] : $previousEvent["DESCRIPTION"];
        $MANAGER = !empty($_POST["Manager"]) ? $_POST["Manager"] : $previousEvent["MANAGER"];
        $EVENT_PLANNER = !empty($_POST["Planificateur"]) ? $_POST["Planificateur"] : $previousEvent["EVENT_PLANNER"];
        $DJ = !empty($_POST["DJ"]) ? $_POST["DJ"] : $previousEvent["DJ"];
        $THEME = !empty($_POST["Theme"]) ? $_POST["Theme"] : $previousEvent["THEME"];
        $TYPE = !empty($_POST["Type"]) ? $_POST["Type"] : $previousEvent["TYPE"];
        $LOCATION = !empty($_POST["Location"]) ? $_POST["Location"] : $previousEvent["LOCATION"];
        $RENTAL_FEE = !empty($_POST["Frais"]) ? $_POST["Frais"] : $previousEvent["RENTAL_FEE"];
        $PLAYLIST = !empty($_POST["Playlist"]) ? $_POST["Playlist"] : $previousEvent["PLAYLIST"];

        $flag = 0; // Permet de vérifier si les données reçues sont cohérentes
        $date = date("Y-m-d");
        $date = strtotime($date);
        if ($date >= strtotime($previousEvent["DATE"])) {
            $flag = 1;
        }
        $query = "SELECT * FROM EVENT WHERE MANAGER = :MANAGER AND DATE = :DATE AND ID <> :ID";
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(":MANAGER", $MANAGER);
        $stmt->bindParam(":DATE", $previousEvent["DATE"]);
        $stmt->bindParam(":ID", $_POST["event"]);
        $stmt->execute();
        $dispoManager = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $query = "SELECT * FROM EVENT WHERE DJ = :DJ AND DATE = :DATE AND ID <> :ID";
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(":DJ", $DJ);
        $stmt->bindParam(":DATE", $previousEvent["DATE"]);
        $stmt->bindParam(":ID", $_POST["event"]);
        $stmt->execute();
        $dispoDJ = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $query = "SELECT * FROM EVENT WHERE EVENT_PLANNER = :EVENT_PLANNER AND DATE = :DATE AND ID <> :ID";
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(":EVENT_PLANNER", $EVENT_PLANNER);
        $stmt->bindParam(":DATE", $previousEvent["DATE"]);
        $stmt->bindParam(":ID", $_POST["event"]);
        $stmt->execute();
        $dispoPlanificateur = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($dispoDJ) || !empty($dispoManager) || !empty($dispoPlanificateur)) {
            $flag = 1;
        }

        if ($flag == 0) {
            // Requête pour modifier les informations de l'événement
            $stmt = $bdd->prepare("UPDATE EVENT SET NAME=?, DESCRIPTION=?, MANAGER=?, EVENT_PLANNER=?, 
                                    DJ=?, THEME=?, TYPE=?, LOCATION=?, RENTAL_FEE=?, PLAYLIST=? WHERE ID=? ");

            $stmt->execute([$NAME, $DESCRIPTION, $MANAGER, $EVENT_PLANNER, $DJ, $THEME, $TYPE, $LOCATION, $RENTAL_FEE, $PLAYLIST, $_POST["event"]]);
            if ($stmt->rowCount() > 0) {
                $bdd->commit();
                echo "Événement modifié";
            } else {
                $error = $stmt->errorInfo();
                echo "Erreur lors de la modification : " . $error[2];
            }
        } else {
            echo "Soit l'événement choisi n'est pas à venir, soit l'un des employés n'est pas disponible à cette date";
        }
    }

} catch (PDOException $e) {
    $bdd->rollback();
    echo "Erreur : " . $e->getMessage();
}
?>
</body>
</html>
