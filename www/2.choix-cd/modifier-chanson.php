<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Modifier la chanson</title>
    </head>
    <body>
    <?php
    // Vérifier que le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Récupérer les infos transmises par le formulaire
        $chanson = $_POST['chansons'];
        $titre = $_POST['m-Titre'];
        $artiste = $_POST['m-Artiste'];
        $duree = $_POST['m-Duree'];
        $genre = $_POST['m-Genre'];

        // Se connecter à la base de données
        try {
            $bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
            if ($bdd == NULL) {
                die("Problème de connexion");
            }

            $bdd->beginTransaction(); // Début de la transaction

            // Préparer la requête d'UPDATE
            $stmt = $bdd->prepare("UPDATE SONG SET TITLE=?, ARTIST=?, DURATION=?, GENRE=? WHERE TRACK_NUMBER=?");

            // Exécuter la requête avec les nouvelles valeurs des champs
            $stmt->execute([$titre, $artiste, $duree, $genre, $chanson]);

            if ($stmt->rowCount() > 0) {
                echo "La chanson a été modifiée avec succès !";
                $bdd->commit(); // Valider la transaction
            } else {
                $error = $stmt->errorInfo();
                echo "Une erreur est survenue lors de la modification de la chanson !(" . $error[2] . ")";
                $bdd->rollBack(); // Annuler la transaction en cas d'erreur
            }
            
        } catch (PDOException $e) {
            $bdd->rollBack(); // Annuler la transaction en cas d'erreur
            echo "Une erreur est survenue lors de la modification : " . $e->getMessage();
        }
        // Fermer la connexion à la base de données
        $bdd = null;
    }
    ?>
    </body>
</html>
