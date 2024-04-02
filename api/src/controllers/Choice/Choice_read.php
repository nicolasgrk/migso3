<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/Choice.php';

$database = new Database();
$db = $database->getConnection();

$choice = new Choice($db);

$stmt = $choice->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $choices_arr = array();
    $choices_arr["data"] = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $choice_item = array(
            "ChoiceID" => $ChoiceID,
            "QuestionID" => $QuestionID,
            "ChoiceText" => $ChoiceText
        );

        array_push($choices_arr["data"], $choice_item);
    }

    http_response_code(200);
    echo json_encode($choices_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Aucun choix trouvé."));
}
?>
