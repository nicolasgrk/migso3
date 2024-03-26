<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/Question.php';

$database = new Database();
$db = $database->getConnection();

$question = new Question($db);

$stmt = $question->readWithChoice();
$num = $stmt->rowCount();

if ($num > 0) {
    $questions_arr = array();
    $questions_arr["data"] = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $questionID = $row['QuestionID'];

        // Ne pas ajouter en double les questions
        if (!isset($questions_arr["data"][$questionID])) {
            $questions_arr["data"][$questionID] = array(
                "QuestionID" => $questionID,
                "QuestionText" => $row['QuestionText'],
                "Choices" => array()
            );
        }

        if ($row['ChoiceID']) {
            array_push($questions_arr["data"][$questionID]["Choices"], array(
                "ChoiceID" => $row['ChoiceID'],
                "ChoiceText" => $row['ChoiceText']
            ));
        }
    }

    // Réindexer pour avoir une liste
    $questions_arr["data"] = array_values($questions_arr["data"]);

    http_response_code(200);
    echo json_encode($questions_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Aucune question trouvée."));
}
?>
