<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/Answer.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['AnswerID'])) {
        $database = new Database();
        $db = $database->getConnection();
        $answer = new Answer($db);

        $answer->AnswerID = $_GET['AnswerID'];
        $answer->readOne();

        if ($answer->UserID != null) {
            $answer_arr = array(
                "AnswerID" => $answer->AnswerID,
                "UserID" => $answer->UserID,
                "QuestionID" => $answer->QuestionID,
                "ChoiceID" => $answer->ChoiceID
            );

            http_response_code(200);
            echo json_encode($answer_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Réponse non trouvée."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "AnswerID manquant."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
