<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/Answer.php';
include_once '../../models/Compatibility.php'; // Assurez-vous d'avoir ce fichier et la classe

// Obtenir la connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Préparer un objet pour gérer les réponses. 
// Remplacez 'Answer' par le nom réel de votre classe de gestion des réponses si nécessaire.
// $answer = new Answer($db); 

// Récupérer les données envoyées
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->answers) && is_array($data->answers)) {
    $errors = [];
    $successCount = 0;
    
    foreach ($data->answers as $answer) {
        $query = $db->prepare("INSERT INTO answers (UserID, QuestionID, ChoiceID) VALUES (:UserID, :QuestionID, :ChoiceID)");
        
        $query->bindParam(":UserID", $answer->UserID);
        $query->bindParam(":QuestionID", $answer->QuestionID);
        $query->bindParam(":ChoiceID", $answer->ChoiceID);
        
        if($query->execute()) {
            $successCount++;
        } else {
            $errors[] = "Erreur lors de l'insertion de la réponse pour QuestionID: {$answer->QuestionID}";
        }
    }

    
    if ($successCount > 0 && empty($errors)) {
        http_response_code(201);
        calculateCompatibility($answer->UserID, $db);
        echo json_encode(array("message" => "$successCount réponses ont été enregistrées avec succès."));
    } elseif ($successCount > 0) {
        http_response_code(207); // Réponse partielle réussie
        echo json_encode(array("message" => "Certaines réponses ont été enregistrées avec succès.", "errors" => $errors));
    } else {
        http_response_code(503); // Service indisponible ou erreur interne
        echo json_encode(array("message" => "Aucune réponse n'a été enregistrée.", "errors" => $errors));
    }
} else {
    http_response_code(400); // Mauvaise requête
    echo json_encode(array("message" => "Données de réponses manquantes ou malformées."));
}
// Fonction de calcul de la compatibilité
function calculateCompatibility($currentUser, $db) {
    // Récupérer les réponses de l'utilisateur actuel
    $queryCurrentUser = $db->prepare("SELECT * FROM answers WHERE UserID = :currentUser");
    $queryCurrentUser->bindParam(":currentUser", $currentUser);
    $queryCurrentUser->execute();
    $answersCurrentUser = $queryCurrentUser->fetchAll(PDO::FETCH_ASSOC);

    // Obtenir la liste de tous les autres utilisateurs
    $queryOtherUsers = $db->prepare("SELECT DISTINCT UserID FROM answers WHERE UserID != :currentUser");
    $queryOtherUsers->bindParam(":currentUser", $currentUser);
    $queryOtherUsers->execute();
    $otherUsers = $queryOtherUsers->fetchAll(PDO::FETCH_ASSOC);

    // Pour chaque utilisateur, comparer les réponses avec celles de l'utilisateur actuel
    foreach ($otherUsers as $otherUser) {
        $matchingAnswers = 0;
        $totalQuestions = 0;

        // Récupérer les réponses de cet autre utilisateur
        $queryOtherUserAnswers = $db->prepare("SELECT * FROM answers WHERE UserID = :otherUserID");
        $queryOtherUserAnswers->bindParam(":otherUserID", $otherUser['UserID']);
        $queryOtherUserAnswers->execute();
        $otherUserAnswers = $queryOtherUserAnswers->fetchAll(PDO::FETCH_ASSOC);

        // Comparer les réponses
        foreach ($answersCurrentUser as $answerCurrentUser) {
            foreach ($otherUserAnswers as $otherUserAnswer) {
                if ($answerCurrentUser['QuestionID'] === $otherUserAnswer['QuestionID']) {
                    $totalQuestions++;
                    if ($answerCurrentUser['ChoiceID'] === $otherUserAnswer['ChoiceID']) {
                        $matchingAnswers++;
                    }
                    break; // Sortir de la boucle interne dès qu'une correspondance de question est trouvée
                }
            }
        }

        if ($totalQuestions > 0) {
            $compatibilityPercent = ($matchingAnswers / $totalQuestions) * 100;

            // Enregistrer le score de compatibilité dans la base de données
            $queryInsertCompatibility = $db->prepare("INSERT INTO compatibility (UserID1, UserID2, CompatibilityPercent) VALUES (:UserID1, :UserID2, :CompatibilityPercent)");
            $queryInsertCompatibility->bindParam(":UserID1", $currentUser);
            $queryInsertCompatibility->bindParam(":UserID2", $otherUser['UserID']);
            $queryInsertCompatibility->bindParam(":CompatibilityPercent", $compatibilityPercent);
            $queryInsertCompatibility->execute();
        }
    }
}


?>
