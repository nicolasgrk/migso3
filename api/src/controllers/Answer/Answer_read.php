<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/Answer.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $database = new Database();
    $db = $database->getConnection();
    $answer = new Answer($db);

    $stmt = $answer->read();
    $num = $stmt->rowCount();

    if ($num > 0) {
        $answers_arr = array();
        $answers_arr["data"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $answer_item = array(
                "AnswerID" => $AnswerID,
                "UserID" => $UserID,
                "QuestionID" => $QuestionID,
                "ChoiceID" => $ChoiceID
            );

            array_push($answers_arr["data"], $answer_item);
        }

        http_response_code(200);
        echo json_encode($answers_arr);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Aucune réponse trouvée."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
