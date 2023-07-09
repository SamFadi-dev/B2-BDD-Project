<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Ajouter le lieu</title>
    </head>
    <body>
    <?php
    // Vérifier que le formulaire a été soumis
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {

            $bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
            if ($bdd == NULL) {
                die("Problème de connection");
            }
            $bdd->beginTransaction();

            // Récupérer les données du formulaire
            $rue = $_POST['Rue'];
            $ville = $_POST['Ville'];
            $code_postale = $_POST['Code_Postale'];
            $pays = $_POST['Pays'];
            $commentaire = $_POST['Commentaire'];

            // Requête SQL pour récupérer l'ID maximal de la table LOCATION
            $query = 'SELECT MAX(ID) FROM LOCATION';
            $stmt = $bdd->query($query);
            $id = $stmt->fetchColumn();
            $id++;

            // Vérifier si les champs sont remplis
            if (empty($rue) || empty($ville) || empty($code_postale) || empty($pays) || empty($commentaire)) {
                echo "Veuillez remplir tous les champs du formulaire.";
                exit();
            }

            $query = 'INSERT INTO LOCATION (ID, STREET, CITY, POSTAL_CODE, COUNTRY, COMMENT) 
                      VALUES (:id, :rue, :ville, :code_postale, :pays, :commentaire)';
            $stmt = $bdd->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':rue', $rue);
            $stmt->bindValue(':ville', $ville);
            $stmt->bindValue(':code_postale', $code_postale);
            $stmt->bindValue(':pays', $pays);
            $stmt->bindValue(':commentaire', $commentaire);

            // Exécuter la requête SQL
            if ($stmt->execute()) {
                echo "Nouveau lieu ajouté avec succès.";
                $bdd->commit(); // Commit la transaction
            } else {
                $error = $stmt->errorInfo();
                echo "Erreur lors de l'ajout du lieu." . $error[2];
                $bdd->rollBack(); // Rollback si erreur
            }

        } catch (PDOException $e) {
            $bdd->rollBack(); // Rollback si erreur
            echo "Erreur lors de l'exécution de la transaction: " . $e->getMessage();
        }
    } 
    ?>
    </body>
</html>
