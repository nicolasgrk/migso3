<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/Answer.php';
include_once '../../models/Compatibility.php'; // Assurez-vous d'avoir ce fichier et la classe

// Obtenir la connexion à la base de données
$database = new Database();
$db = $database->getConnection();
$currentUser = 3;


    // Récupérer les réponses de l'utilisateur actuel
    $queryCurrentUser = $db->prepare("SELECT * FROM answers WHERE UserID = :currentUser");
    $queryCurrentUser->bindParam(":currentUser", $currentUser);
    $queryCurrentUser->execute();
    $answersCurrentUser = $queryCurrentUser->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les réponses de tous les autres utilisateurs (excluant l'utilisateur actuel)
    $queryAllUsers = $db->prepare("SELECT * FROM answers WHERE UserID != :currentUser");
    $queryAllUsers->bindParam(":currentUser", $currentUser);
    $queryAllUsers->execute();
    $allUsersAnswers = $queryAllUsers->fetchAll(PDO::FETCH_ASSOC);

    // Initialiser un tableau pour stocker les scores de compatibilité
    $compatibilityScores = [];
    $matchingAnswers = 0; // Compteur pour les réponses identiques

    foreach($answersCurrentUser as $answerCurrentUser){
        foreach($allUsersAnswers as $otherUserAnswers){
            if ($answerCurrentUser['QuestionID'] === $otherUserAnswers['QuestionID']) {
                if ($answerCurrentUser['ChoiceID'] === $otherUserAnswers['ChoiceID']) {
                    $matchingAnswers++;
                    break; // Sortir de la boucle interne si une réponse est identique
                }
            }
        }
    }
    $totalQuestions = count($answersCurrentUser);
    $compatibilityPercent = ($matchingAnswers / $totalQuestions) * 100;        

  
    // Ajouter le score de compatibilité à la liste
    $compatibilityScores[] = [
        'UserID1' => $currentUser,
            'UserID2' => $otherUserAnswers['UserID'],
            'CompatibilityPercent' => $compatibilityPercent
        ];


    // Enregistrer les scores de compatibilité dans la base de données
    // foreach ($compatibilityScores as $compatibility) {
    //     $queryInsertCompatibility = $db->prepare("INSERT INTO compatibility (UserID1, UserID2, CompatibilityPercent) VALUES (:UserID1, :UserID2, :CompatibilityPercent)");
    //     $queryInsertCompatibility->bindParam(":UserID1", $compatibility['UserID1']);
    //     $queryInsertCompatibility->bindParam(":UserID2", $compatibility['UserID2']);
    //     $queryInsertCompatibility->bindParam(":CompatibilityPercent", $compatibility['CompatibilityPercent']);
    //     $queryInsertCompatibility->execute();
    // }
