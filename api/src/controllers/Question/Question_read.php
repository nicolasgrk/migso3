<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/Question.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $database = new Database();
    $db = $database->getConnection();
    $question = new Question($db);

    $stmt = $question->read();
    $num = $stmt->rowCount();

    if ($num > 0) {
        $questions_arr = array();
        $questions_arr["data"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $question_item = array(
                "QuestionID" => $QuestionID,
                "QuestionText" => $QuestionText
            );

            array_push($questions_arr["data"], $question_item);
        }

        http_response_code(200);
        echo json_encode($questions_arr);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Aucune question trouvée."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>