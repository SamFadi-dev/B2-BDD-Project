<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Modifier le lieu</title>
    </head>
    <body>
    <?php
    // Vérifier que le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Récupérer les infos transmis par le formulaire
        $lieu_id = $_POST['ville'];
        $rue = $_POST['Rue'];
        $ville = $_POST['Ville'];
        $code_postale = $_POST['Code_Postale'];
        $pays = $_POST['Pays'];
        $commentaire = $_POST['Commentaire'];

        // Se connecter à la base de données
        try {
            $bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
            if ($bdd == NULL) {
                die("Problème de connection");
            }

            $bdd->beginTransaction(); // Begin transaction

            // Préparer la requête d'UPDATE
            $stmt = $bdd->prepare("UPDATE LOCATION SET STREET=?, CITY=?, POSTAL_CODE=?, COUNTRY=?, COMMENT=? WHERE ID=?");

            // Récupérer les nouvelles valeurs des champs
            $id = $lieu_id; // ID de la ligne à modifier
            $street = $rue;
            $city = $ville;
            $postalCode = $code_postale;
            $country = $pays;
            $comment = $commentaire;

            // Exécuter la requête avec les nouvelles valeurs des champs
            $stmt->execute([$street, $city, $postalCode, $country, $comment, $id]);

            // Vérifier si la modification a réussi
            if ($stmt->rowCount() > 0) {
                echo "La localisation a été modifiée avec succès !";
                $bdd->commit(); // Commit the transaction
            } else {
                echo "Une erreur est survenue lors de la modification de la localisation.";
                $bdd->rollBack(); // Rollback the transaction
            }
            
        } catch (PDOException $e) {
            $bdd->rollBack(); // Rollback the transaction in case of an exception
            echo "Une erreur est survenue lors de la modification : " . $e->getMessage();
        }
        // Fermer la connexion à la base de données
        $bdd = null;
    }
    ?>
    </body>
</html>
