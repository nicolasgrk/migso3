<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/Question.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['QuestionID'])) {
        $database = new Database();
        $db = $database->getConnection();
        $question = new Question($db);

        $question->QuestionID = $_GET['QuestionID'];
        $question->readOne();

        if ($question->QuestionText != null) {
            $question_arr = array(
                "QuestionID" => $question->QuestionID,
                "QuestionText" => $question->QuestionText
            );

            http_response_code(200);
            echo json_encode($question_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Question non trouvée."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "QuestionID manquant."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
