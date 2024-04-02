<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/Question.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $question = new Question($db);

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->QuestionID) && !empty($data->QuestionText)) {
        $question->QuestionID = $data->QuestionID;
        $question->QuestionText = $data->QuestionText;

        if ($question->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "La question a été mise à jour."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de mettre à jour la question."));
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
