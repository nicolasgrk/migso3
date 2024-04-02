<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/Compatibility.php';

$database = new Database();
$db = $database->getConnection();

// Récupérer tous les utilisateurs
$queryUsers = "SELECT UserID, FirstName, LastName FROM users";
$stmtUsers = $db->prepare($queryUsers);
$stmtUsers->execute();
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

// Initialiser la matrice de compatibilité
$compatibilityMatrix = [];

// Parcourir chaque utilisateur et récupérer ses compatibilités
foreach ($users as $user) {
    $userID = $user['UserID'];

    // Récupérer la compatibilité de l'utilisateur avec les autres
    $queryCompatibility = "SELECT c.UserID1, c.UserID2, c.CompatibilityPercent, u1.FirstName AS FirstName1, u1.LastName AS LastName1, u2.FirstName AS FirstName2, u2.LastName AS LastName2 
                           FROM compatibility c
                           JOIN users u1 ON c.UserID1 = u1.UserID
                           JOIN users u2 ON c.UserID2 = u2.UserID
                           WHERE c.UserID1 = :userID OR c.UserID2 = :userID";
    $stmtCompatibility = $db->prepare($queryCompatibility);
    $stmtCompatibility->bindParam(':userID', $userID);
    $stmtCompatibility->execute();
    $compatibilities = $stmtCompatibility->fetchAll(PDO::FETCH_ASSOC);

    // Construire une ligne de la matrice
    $compatibilityRow = array('UserID' => $userID, 'FirstName' => $user['FirstName'], 'LastName' => $user['LastName'], 'compatibility' => []);

    // Parcourir tous les utilisateurs pour remplir la ligne de compatibilité
    foreach ($users as $otherUser) {
        if ($userID == $otherUser['UserID']) {
            // Pas de pourcentage de compatibilité pour lui-même
            $compatibilityRow['compatibility'][$otherUser['UserID']] = null;
        } else {
            // Trouver le pourcentage de compatibilité avec l'autre utilisateur
            $found = false;
            foreach ($compatibilities as $comp) {
                if (($comp['UserID1'] == $userID && $comp['UserID2'] == $otherUser['UserID']) ||
                    ($comp['UserID2'] == $userID && $comp['UserID1'] == $otherUser['UserID'])) {
                    $compatibilityRow['compatibility'][$otherUser['UserID']] = $comp['CompatibilityPercent'];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                // Si pas de compatibilité enregistrée, mettre une valeur par défaut ou null
                $compatibilityRow['compatibility'][$otherUser['UserID']] = null;
            }
        }
    }

    // Ajouter la ligne au tableau de la matrice
    $compatibilityMatrix[] = $compatibilityRow;
}

// Envoyer la matrice de compatibilité en réponse
echo json_encode($compatibilityMatrix);
