<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/Answer.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $answer = new Answer($db);

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->AnswerID) && !empty($data->UserID) && !empty($data->QuestionID) && !empty($data->ChoiceID)) {
        $answer->AnswerID = $data->AnswerID;
        $answer->UserID = $data->UserID;
        $answer->QuestionID = $data->QuestionID;
        $answer->ChoiceID = $data->ChoiceID;

        if ($answer->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "La réponse a été mise à jour."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de mettre à jour la réponse."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Données incomplètes."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
