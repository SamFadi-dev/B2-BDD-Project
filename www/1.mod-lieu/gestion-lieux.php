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
    <script>
        //script permettant de pré-remplir les champs du formulaire de modification
        function fillFormFields() {
            var selectedOption = document.getElementById("ville-modifier").value;
            var villeDetails = document.getElementById("ville-details-" + selectedOption).innerHTML;
            var detailsArray = villeDetails.split("|");
            document.getElementById("Rue-modifier").value = detailsArray[0].trim();
            document.getElementById("Ville-modifier").value = detailsArray[1].trim();
            document.getElementById("Code_Postale-modifier").value = detailsArray[2].trim();
            document.getElementById("Pays-modifier").value = detailsArray[3].trim();
            document.getElementById("Commentaire-modifier").value = detailsArray[4].trim();
        }
    </script>

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
if ($bdd == NULL)
    die("Problème de connection");
    
$query = 'SELECT * FROM LOCATION';
$stmt = $bdd->prepare($query);
$stmt->execute();
$villes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Ajout, modification ou suppression d'une localisation dans la base de données</h1>

<div class="form-separator"></div>

<h2>Ajouter un lieu</h2>
<form method="post" action="ajouter.php">
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

<div class="form-separator"></div>

<h2>Supprimer un lieu</h2>
<form method="post" action="supprimer.php">
    <label for="ville">Sélectionnez un lieu à supprimer :</label>
    <select name="ville" id="ville">
        <?php foreach ($villes as $ville): ?>
            <option value="<?= $ville['ID'] ?>"><?= $ville['ID'] ?>. <?= $ville['STREET'] ?>, <?= $ville['CITY'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Valider">
</form>

<div class="form-separator"></div>

<h2>Modifier un lieu</h2>
<form method="post" action="modifier.php">
    <p>
        <label for="ville-modifier">Sélectionnez un lieu à modifier :</label>
        <select name="ville" id="ville-modifier" onchange="fillFormFields()">
            <?php foreach ($villes as $ville): ?>
                <option value="<?= $ville['ID'] ?>"> <?= $ville['ID'] ?>. <?= $ville['STREET'] ?>, <?= $ville['CITY'] ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="Rue-modifier">Rue :</label>
        <input type="text" name="Rue" id="Rue-modifier" required>
        <br>
        <label for="Ville-modifier">Ville :</label>
        <input type="text" name="Ville" id="Ville-modifier" required>
        <br>
        <label for="Code_Postale-modifier">Code Postale :</label>
        <input type="text" name="Code_Postale" id="Code_Postale-modifier" required>
        <br>
        <label for="Pays-modifier">Pays :</label>
        <input type="text" name="Pays" id="Pays-modifier" required>
        <br>
        <label for="Commentaire-modifier">Commentaire :</label>
        <input type="text" name="Commentaire" id="Commentaire-modifier" required>
        <br>
        <input type="submit" value="Envoyer">
        <input type="button" value="Actualiser" onclick="fillFormFields()">
    </p>
</form>

<div class="form-separator"></div>

<h2>Liste des lieux</h2>
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
    <?php foreach ($villes as $ville): ?>
        <tr>
            <td><?= $ville['ID'] ?></td>
            <td><?= $ville['STREET'] ?></td>
            <td><?= $ville['CITY'] ?></td>
            <td><?= $ville['POSTAL_CODE'] ?></td>
            <td><?= $ville['COUNTRY'] ?></td>
            <td><?= $ville['COMMENT'] ?></td>
        </tr>
        <tr style="display: none;">
            <td id="ville-details-<?= $ville['ID'] ?>">
                <?= $ville['STREET'] ?>|<?= $ville['CITY'] ?>|<?= $ville['POSTAL_CODE'] ?>|<?= $ville['COUNTRY'] ?>|<?= $ville['COMMENT'] ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
