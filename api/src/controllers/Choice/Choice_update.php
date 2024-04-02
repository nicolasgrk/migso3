<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/Choice.php';

$database = new Database();
$db = $database->getConnection();

$choice = new Choice($db);

$data = json_decode(file_get_contents("php://input"));

$choice->ChoiceID = $data->ChoiceID;
$choice->QuestionID = $data->QuestionID;
$choice->ChoiceText = $data->ChoiceText;

if ($choice->update()) {
    http_response_code(200);
    echo json_encode(array("message" => "Le choix a été mis à jour avec succès."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Impossible de mettre à jour le choix."));
}
?>
