<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/Choice.php';

// Obtenir la connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Préparer un objet Choice
$choice = new Choice($db);

// Récupérer les données envoyées
$data = json_decode(file_get_contents("php://input"));

// Vérifier que les données nécessaires sont présentes
if (!empty($data->QuestionID) && !empty($data->ChoiceText)) {
    // Assigner les valeurs à l'objet Choice
    $choice->QuestionID = $data->QuestionID;
    $choice->ChoiceText = $data->ChoiceText;

    // Tenter de créer le choix
    if ($choice->create()) {
        // Réponse 201 - Créé
        http_response_code(201);
        echo json_encode(array("message" => "Le choix a été créé avec succès."));
    } else {
        // Réponse 503 - Service indisponible
        http_response_code(503);
        echo json_encode(array("message" => "Impossible de créer le choix."));
    }
} else {
    // Réponse 400 - Requête incorrecte
    http_response_code(400);
    echo json_encode(array("message" => "Impossible de créer le choix. Des données sont manquantes."));
}

?>
