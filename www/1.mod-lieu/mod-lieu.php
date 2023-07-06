<?php
//-------------------------------------------------
//-----------CODE PRINCIPALE QUESTION 1------------
//-------------------------------------------------
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>1. Gestion des localisations</title>
    </head>
    <body>
        <?php
        $bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
        if ($bdd == NULL)
            die("Problème de connection");
        $query = 'SELECT * FROM LOCATION';
        $stmt = $bdd->prepare($query);
        $stmt->execute();
        $villes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            } else {
                $error = $stmt->errorInfo();
                echo "Erreur lors de l'ajout du lieu." . $error[2];
            }
        }
        ?>

        <h1>Ajout ou modification d'une localisation dans la base de données</h1>
        <h2>Ajouter un lieu</h2>
            <form method="post" action="mod-lieu.php">
                <p>
                    <label for="Rue">Rue :</label>
                    <input type="text" name="Rue" id="Rue" required>
                    <br>
                    <label for="Ville">Ville :</label>
                    <input type="text" name="Ville" id="Ville" required>
                    <br>
                    <label for="Code_Postale">Code Postale :</label>
                    <input type="text" name="Code_Postale" id="Code_Postale" required>
                    <br>
                    <label for="Pays">Pays :</label>
                    <input type="text" name="Pays" id="Pays" required>
                    <br>
                    <label for="Commentaire">Commentaire :</label>
                    <input type="text" name="Commentaire" id="Commentaire" required>
                    <br>
                    <input type="submit" value="Envoyer">
                </p>
            </form>

        <h2>Supprimer un lieu</h2>
            <form method="post" action="supprimer.php">
            <label for="ville">Sélectionnez un lieu à supprimer :</label>
            <select name="ville" id="ville">
                <?php foreach ($villes as $ville): ?>
                    <option value="<?= $ville['ID'] ?>"><?= $ville['ID'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Valider">
            </form>

        <h2>Modifier un lieu</h2>
            <form method="post" action="modifier.php">
                <p>
                    <label for="ville">Sélectionnez un lieu à modifier :</label>
                    <select name="ville" id="ville">
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?= $ville['ID'] ?>"><?= $ville['ID'] ?></option>
                        <?php endforeach; ?>
                    </select><br>
                    <label for="Rue">Rue :</label>
                    <input type="text" name="Rue" id="Rue" required>
                    <br>
                    <label for="Ville">Ville :</label>
                    <input type="text" name="Ville" id="Ville" required>
                    <br>
                    <label for="Code_Postale">Code Postale :</label>
                    <input type="text" name="Code_Postale" id="Code_Postale" required>
                    <br>
                    <label for="Pays">Pays :</label>
                    <input type="text" name="Pays" id="Pays" required>
                    <br>
                    <label for="Commentaire">Commentaire :</label>
                    <input type="text" name="Commentaire" id="Commentaire" required>
                    <br>
                    <input type="submit" value="Envoyer">
                </p>
            </form>

        <h2> Liste des localisations </h2>
            <table>
            <thead>
                <tr>
                <th>ID</th>
                <th>Rue</th>
                <th>Ville</th>
                <th>Code postal</th>
                <th>Pays</th>
                <th>Commentaire</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Requête SQL pour récupérer les données de la table LOCATION
                $query = 'SELECT * FROM LOCATION';
                $stmt = $bdd->query($query);
                while ($row = $stmt->fetch()) {
                // Affichage de chaque ligne de la table sous forme de ligne de tableau
                echo "<tr>";
                echo "<td>" . $row['ID'] . "</td>";
                echo "<td>" . $row['STREET'] . "</td>";
                echo "<td>" . $row['CITY'] . "</td>";
                echo "<td>" . $row['POSTAL_CODE'] . "</td>";
                echo "<td>" . $row['COUNTRY'] . "</td>";
                echo "<td>" . $row['COMMENT'] . "</td>";
                echo "</tr>";
                }
                ?>
            </tbody>
            </table>    
    </body>
</html>