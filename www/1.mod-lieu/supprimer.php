<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Supprimer le lieu</title>
    </head>
    <body>
    <?php
    // Vérifier que le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Récupérer l'ID transmis par le formulaire
        $ville_id = $_POST['ville'];

        // Se connecter à la base de données
        try {
            $bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
            if ($bdd == NULL) {
                die("Problème de connection");
            }

            $bdd->beginTransaction();

            // Vérifier d'abord si la ligne est liée à d'autres lignes de la table EVENT
            $stmt = $bdd->prepare("SELECT COUNT(*) FROM EVENT WHERE LOCATION = ?");
            $stmt->execute([$ville_id]);
            $rowCount = $stmt->fetchColumn();

            if ($rowCount > 0) {
                echo "La ville ne peut pas être supprimée car elle est liée à d'autres événements.";
                $bdd->rollBack();
            } else {
                // Supprimer la ligne de la table LOCATION
                $stmt = $bdd->prepare("DELETE FROM LOCATION WHERE ID = ?");
                $stmt->execute([$ville_id]);

                // Vérifier si la suppression a réussi
                if ($stmt->rowCount() > 0) {
                    echo "La ville a été supprimée avec succès !";
                    $bdd->commit();
                } else {
                    echo "Une erreur est survenue lors de la suppression de la ville.";
                    $bdd->rollBack();
                }
            }

        } catch (PDOException $e) {
            $bdd->rollBack(); //
            echo "Une erreur est survenue lors de la suppression : " . $e->getMessage();
        }
        // Fermer la connexion à la base de données
        $bdd = null;
    }
    ?>
    </body>
</html>
