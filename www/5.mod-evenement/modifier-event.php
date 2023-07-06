<?php
//-------------------------------------------------
//-----------CODE PRINCIPALE QUESTION 5------------
//-------------------------------------------------
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>5. Modifier événement </title>
    </head>
    <body>
    <?php
    $bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
    if($bdd == NULL){
        die("Problème de connection");
    }

    //Requetes pour récupérer toutes les informations sur les événements.

    $query = 'SELECT * FROM EVENT';
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $events = $stmt->fetchALL(PDO::FETCH_ASSOC);

    $query = 'SELECT * FROM MANAGER';
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $managers = $stmt->fetchALL(PDO::FETCH_ASSOC);

    $query = 'SELECT * FROM DJ';
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $djs = $stmt->fetchALL(PDO::FETCH_ASSOC);

    $query = 'SELECT * FROM EVENTPLANNER';
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $planners = $stmt->fetchALL(PDO::FETCH_ASSOC);

    $query = 'SELECT * FROM LOCATION';
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $locations = $stmt->fetchALL(PDO::FETCH_ASSOC);

    $query = 'SELECT * FROM THEME';
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $themes = $stmt->fetchALL(PDO::FETCH_ASSOC);

    $query = 'SELECT * FROM PLAYLIST';
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $playlists = $stmt->fetchALL(PDO::FETCH_ASSOC);
    if($_SERVER["REQUEST_METHOD"] === 'POST'){
        //Obtiens les informations actuelle de l'événement a modifier
        $query = "SELECT * FROM EVENT WHERE ID = :ID";
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(":ID", $_POST["event"]);
        $stmt->execute();

        $previousEvent = $stmt->fetchALL(PDO::FETCH_ASSOC);
        //Puisque les champs ne sont pas obligatoires, on regarde s'ils ont été rempli.
        //Si le champ n'a pas été rempli, on conserve l'ancien et sinon on récupère le nouveau.
        $NAME = isset($_POST["Nom"]) ? $_POST["Nom"] : $previousEvent["NAME"];
        $DESCRIPTION = isset($_POST["Description"]) ? $_POST["Description"] : $previousEvent["DESCRIPTION"];
        $MANAGER = isset($_POST["Manager"]) ? $_POST["Manager"] : $previousEvent["MANAGER"];
        $EVENT_PLANNER = isset($_POST["Planificateur"]) ? $_POST["Planificateur"] : $previousEvent["EVENT_PLANNER"];
        $DJ = isset($_POST["DJ"]) ? $_POST["DJ"] : $previousEvent["DJ"];
        $THEME = isset($_POST["Theme"]) ? $_POST["Theme"] : $previousEvent["THEME"];
        $TYPE = isset($_POST["Type"]) ? $_POST["Type"] : $previousEvent["TYPE"];
        $LOCATION = isset($_POST["Location"]) ? $_POST["Location"] : $previousEvent["LOCATION"];
        $RENTAL_FEE = isset($_POST["Frais"]) ? $_POST["Frais"] : $previousEvent["RENTAL_FEE"];
        $PLAYLIST = isset($_POST["Playlist"]) ? $_POST["Playlist"] : $previousEvent["PLAYLIST"];

        $flag = 0; //Permet de vérifier si les données recues sont cohérentes.
        $date = date("Y-m-d");
        $date = strtotime($date);
        if($date >= strtotime($previousEvent["DATE"])){
            $flag = 1;
        }
        $query = "SELECT * FROM EVENT WHERE MANAGER = :MANAGER AND DATE = :DATE";
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(":MANAGER", $MANAGER);
        $stmt->bindParam(":DATE", $previousEvent["DATE"]);
        $stmt->execute();
        $dispoManager = $stmt->fetchALL(PDO::FETCH_ASSOC);

        $query = "SELECT * FROM EVENT WHERE DJ = :DJ AND DATE = :DATE";
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(":DJ", $DJ);
        $stmt->bindParam(":DATE", $previousEvent["DATE"]);
        $stmt->execute();
        $dispoDJ = $stmt->fetchALL(PDO::FETCH_ASSOC);

        $query = "SELECT * FROM EVENT WHERE EVENT_PLANNER = :EVENT_PLANNER AND DATE = :DATE";
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(":EVENT_PLANNER", $EVENT_PLANNER);
        $stmt->bindParam(":DATE", $previousEvent["DATE"]);
        $stmt->execute();
        $dispoPlanificateur = $stmt->fetchALL(PDO::FETCH_ASSOC);

        if((empty($dispoDJ) == false) || (empty($dispoManager) == false) || (empty($dispoPlanificateur) == false )){
            $flag = 1;
        }

        if($flag == 0){
        //Requete pour modifier les informations de l'événement.
            $stmt = $bdd->prepare("UPDATE EVENT SET NAME=?, DESCRIPTION=?, MANAGER=?, EVENT_PLANNER=?, 
                                  DJ=?, THEME=?, TYPE=?, LOCATION=?, RENTAL_FEE=?, PLAYLIST=? WHERE ID=? ");

            $stmt->execute($NAME, $DESCRIPTION, $MANAGER, $EVENT_PLANNER, $DJ, $THEME, $TYPE, $LOCATION, $RENTAL_FEE, $PLAYLIST, $ID);
            if($stmt->rowCount() > 0 ){
                echo "Evenement modifié";
            }
            else {
                $error = $stmt->errorInfo();
                echo "Erreur lors de la modification" . $error[2];
}
        }
        else{
            echo "Soit l'événement choisi n'est pas a venir ou un des employés n'est pas disponible a cette date";
        }
     }
        ?>
        <h1> Modifier un événement </h1>
            <form method="post" action="modifier-event.php">
                <p>
                    <label for="event"> Sélectionnez un événement a modifier :</label>
                    <select name="event" id="event">
                        <?php foreach($events as $event): ?>
                            <option value="<?=$event["ID"] ?>"><?= $event["ID"]?> - <?= $event['NAME'] ?></option>
                        <?php endforeach; ?>
                    </select><br>
                    <label for="Nom">Nom :</label>
                    <input type="text" name ="Nom" id="Nom" optional> <br>
                    <label for="Description">Description : </label>
                    <input type="text" name="Description" id="Description" optional> <br>
                    <label for="Manager"> Sélectionnez un manager pour l'événement : </label>
                    <select name="Manager" id="Manager">
                        <?php foreach($managers as $manager): ?>
                            <option value="<?=$manager["ID"] ?>"><?= $manager["ID"]?> </option>
                        <?php endforeach; ?>
                    </select> <br>
                    <label for="Planificateur"> Sélectionnez un planificateur pour l'événement : </label>
                    <select name="Planificateur" id="Planificateur">
                        <?php foreach($planners as $planner): ?>
                            <option value="<?=$planner["ID"] ?>"><?= $planner["ID"]?> </option>
                        <?php endforeach; ?>
                    </select><br>
                    <label for="DJ"> Sélectionnez un DJ pour l'événement : </label>
                    <select name="DJ" id="DJ">
                        <?php foreach($djs as $dj): ?>
                            <option value="<?=$dj["ID"] ?>"><?= $dj["ID"]?> </option>
                        <?php endforeach; ?>
                    </select><br>
                    <label for="Theme"> Sélectionnez un thème pour l'événement : </label>
                    <select name="Theme" id="Theme">
                        <?php foreach($themes as $theme): ?>
                            <option value="<?=$theme["NAME"] ?>"><?= $theme["NAME"]?> </option>
                        <?php endforeach; ?>
                    </select><br>
                    <label for="Type"> Type d'événement : </label>
                    <input type="text" name="Type" id="Type" optional> <br>
                    <label for="Location"> Sélectionnez un lieu pour l'événement : </label>
                    <select name="Location" id="Theme">
                        <?php foreach($locations as $location): ?>
                            <option value="<?=$location["ID"] ?>"><?= $location["ID"]?> - <?= $location['CITY'] ?> - <?= $location['STREET'] ?> </option>
                        <?php endforeach; ?>
                    </select><br>
                    <label for="Frais"> Frais de location : </label>
                    <input type="text" name="Frais" id="Frais" optional> <br>
                    <label for="Playlist"> Sélectionnez une playlist pour l'événement : </label>
                    <select name="Playlist" id="Playlist">
                        <?php foreach($playlists as $playlist): ?>
                            <option value="<?=$playlist["NAME"] ?>"><?= $playlist["NAME"]?> </option>
                        <?php endforeach; ?>
                    </select><br>
                    <input type="submit" value="Envoyer">
                </p>
            </form>     
    </body> 
</html>