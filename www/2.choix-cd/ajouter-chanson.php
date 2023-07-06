<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ajouter la chanson</title>
</head>
<body>
<?php
// Vérifier que le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupérer les infos transmises par le formulaire
    $numero_cd = $_POST['numero_cd'];
    $numero_musique = 1;
    $titre = $_POST['Titre'];
    $artiste = $_POST['Artiste'];
    $duree = $_POST['Durée'];
    $genre = $_POST['all_genre'];

    // Se connecter à la base de données
    try {
        $bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
        if ($bdd == NULL) {
            die("Problème de connection");
        }

        //Trouver un TRACK_NUMBER valide en prenant max(TRACK_NUMBER) + 1
        $query = 'SELECT MAX(TRACK_NUMBER) AS MAX_TRACK_NUMBER
                  FROM SONG
                  WHERE CD_NUMBER = :numero_cd';
        $stmt = $bdd->prepare($query);
        $stmt->bindValue(':numero_cd', $numero_cd);
        $stmt->execute();
        $max_numero_musique = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($max_numero_musique)) {
            $numero_musique = 1;
        } else {
            $numero_musique = $max_numero_musique[0]['MAX_TRACK_NUMBER'] + 1;
        }


        $query = "INSERT INTO SONG (CD_NUMBER, TRACK_NUMBER, TITLE, ARTIST, DURATION, GENRE)
          VALUES (:cd_number, :track_number, :title, :artist, :duration, :genre)";
        $stmt = $bdd->prepare($query);
        $stmt->bindValue(':cd_number', $numero_cd);
        $stmt->bindValue(':track_number', $numero_musique);
        $stmt->bindValue(':title', $titre);
        $stmt->bindValue(':artist', $artiste);
        $stmt->bindValue(':duration', $duree);
        $stmt->bindValue(':genre', $genre);

        if ($stmt->execute()) {
            echo "La chanson a été ajoutée avec succès !";
        } else {
            $error = $stmt->errorInfo();
            echo "Une erreur est survenue lors de la modification de la chanson !(" . $error[2] . ")";
        }

    } catch (PDOException $e) {
        echo "Une erreur est survenue lors de la modification : " . $e->getMessage();
    }
    // Fermer la connexion à la base de données
    $bdd = null;
}
?>
</body>
</html>
