<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/Choice.php';

$database = new Database();
$db = $database->getConnection();

$choice = new Choice($db);

// Récupérer l'ID du choix à partir des paramètres de l'URL
$choice->ChoiceID = isset($_GET['ChoiceID']) ? $_GET['ChoiceID'] : die();

$choice->readOne();

if ($choice->QuestionID != null) {
    // Créer un tableau contenant le choix
    $choice_arr = array(
        "ChoiceID" => $choice->ChoiceID,
        "QuestionID" => $choice->QuestionID,
        "ChoiceText" => $choice->ChoiceText
    );

    http_response_code(200);
    echo json_encode($choice_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Choix non trouvé."));
}
?>
