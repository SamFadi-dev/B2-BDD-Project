<?php
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header('Location: acces-bdd.php');
    exit();
}

$bdd = new PDO('mysql:host=ms8db;dbname=groupXX', 'groupXX', 'secret');
if ($bdd == NULL)
    echo "Problème de connection";
// Traitement des filtres de recherche
$nom_filter = isset($_POST['nom_filter']) ? $_POST['nom_filter'] : '';
$numero_filtrer = isset($_POST['numero_filtrer']) ? $_POST['numero_filtrer'] : '';

// Récupération des employées de la base de données
$query = 'SELECT * FROM EMPLOYEE WHERE 1=1';
$parameters = array();

if (!empty($nom_filter)) {
    $query .= " AND LASTNAME LIKE :nom";
    $parameters[':nom'] = '%' . $nom_filter . '%';
}

if (!empty($numero_filtrer)) {
    $query .= " AND ID LIKE :numero";
    $parameters[':numero'] = '%' . $numero_filtrer . '%';
}

$stmt = $bdd->prepare($query);
$stmt->execute($parameters);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Employées</title>
    </head>
    <body>
        <h1>Employées</h1>

        <!-- Formulaire de recherche -->
        <form method="post" action="employee.php">
            <label for="nom_filter">Filtrer par nom :</label>
            <input type="text" name="nom_filter" value="<?= $nom_filter ?>">
            <br>
            <label for="numero_filtrer">Filtrer par numéro :</label>
            <input type="text" name="numero_filtrer" value="<?= $numero_filtrer ?>">
            <br>
            <input type="submit" value="Rechercher">
        </form>

        <!-- Tableau des employées -->
        <table>
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?= htmlentities($employee['ID']) ?></td>
                        <td><?= htmlentities($employee['FIRSTNAME']) ?></td>
                        <td><?= htmlentities($employee['LASTNAME']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Formulaire pour se déconnecter -->
        <form method="post" action="acces-bdd.php">
                <p>
                    <input type="hidden" name="disconnect" value="yes">
                    <input type="submit" value="Deconnection">
                </p>
            </form>
    </body>
</html>
