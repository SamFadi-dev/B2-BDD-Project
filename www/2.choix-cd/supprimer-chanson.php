<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Supprimer une chanson</title>
</head>
<body>
<?php
// Vérifier que le formulaire a été soumis et que l'utilisateur a confirmé la suppression
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Récupérer les infos transmises par le formulaire
    $numero_cd = $_POST['numero_cd'];
    $numero_musique = $_POST['sup_chanson'];

    // Se connecter à la base de données
    try {
        $bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
        if ($bdd == NULL) {
            die("Problème de connexion");
        }

        $bdd->beginTransaction(); // Début de la transaction

        // Supprimer la musique de la table SONG et CONTAINS
        $querySONG = "DELETE FROM SONG WHERE CD_NUMBER = :cd_number AND TRACK_NUMBER = :track_number";
        $queryCONTAINS = "DELETE FROM CONTAINS WHERE CD_NUMBER = :cd_number AND TRACK_NUMBER = :track_number";
        $stmtSONG = $bdd->prepare($querySONG);
        $stmtCONTAINS = $bdd->prepare($queryCONTAINS);
        $stmtSONG->bindValue(':cd_number', $numero_cd);
        $stmtSONG->bindValue(':track_number', $numero_musique);
        $stmtCONTAINS->bindValue(':cd_number', $numero_cd);
        $stmtCONTAINS->bindValue(':track_number', $numero_musique);

        if ($stmtSONG->execute() && $stmtCONTAINS->execute()) {
            echo "La chanson a été supprimée avec succès !";
            $bdd->commit(); // Valider la transaction
        } else {
            $error = $stmt->errorInfo();
            echo "Une erreur est survenue lors de la suppression de la chanson !(" . $error[2] . ")";
            $bdd->rollBack(); // Annuler la transaction en cas d'erreur
        }

    } catch (PDOException $e) {
        $bdd->rollBack(); // Annuler la transaction en cas d'erreur
        echo "Une erreur est survenue lors de la suppression : " . $e->getMessage();
    }
    // Fermer la connexion à la base de données
    $bdd = null;
} 
?>
</body>
</html>
