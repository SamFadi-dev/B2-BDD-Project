<?php
/* This is a PHP script that displays a list of employees from a database and allows the user to filter
the results based on name and number. */

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
$prenom_filter = isset($_POST['prenom_filter']) ? $_POST['prenom_filter'] : '';
$numero_filtrer = isset($_POST['numero_filtrer']) ? $_POST['numero_filtrer'] : '';

// Récupération des employées de la base de données
$query = 'SELECT * FROM EMPLOYEE WHERE 1=1';
$parameters = array();

if (!empty($nom_filter)) {
    $query .= " AND LASTNAME LIKE :nom";
    $parameters[':nom'] = '%' . $nom_filter . '%';
}

if (!empty($prenom_filter)) {
    $query .= " AND FIRSTNAME LIKE :prenom";
    $parameters[':prenom'] = '%' . $prenom_filter . '%';
}

if (!empty($numero_filtrer)) {
    $query .= " AND ID = :numero";
    $parameters[':numero'] = $numero_filtrer;
}

$stmt = $bdd->prepare($query);
$stmt->execute($parameters);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Employees</title>
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
        <h1>Employées</h1>

        <!-- Formulaire de recherche -->
        <form method="post" action="employee.php">
            <label for="nom_filter">Filtrer par nom :</label>
            <input type="text" name="nom_filter" value="<?= $nom_filter ?>">
            <br>
            <label for="numero_filtrer">Filtrer par numéro :</label>
            <input type="text" name="numero_filtrer" value="<?= $numero_filtrer ?>">
            <br>
            <label for="prenom_filter">Filtrer par prénom :</label>
            <input type="text" name="prenom_filter" value="<?= $prenom_filter ?>">
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
                    <input type="submit" value="Déconnexion">
                </p>
            </form>
    </body>
</html>
