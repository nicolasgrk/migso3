<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/Question.php';

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $database = new Database();
    $db = $database->getConnection();
    $question = new Question($db);

    if (!empty($data['QuestionID'])) {
        $question->QuestionID = $data['QuestionID'];

        if ($question->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "La question a été supprimée."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de supprimer la question."));
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
