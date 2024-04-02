<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/Choice.php';

$database = new Database();
$db = $database->getConnection();

$choice = new Choice($db);

// Récupérer l'ID du choix à supprimer depuis le corps de la requête
$data = json_decode(file_get_contents("php://input"));

$choice->ChoiceID = $data->ChoiceID;

if ($choice->delete()) {
    http_response_code(200);
    echo json_encode(array("message" => "Le choix a été supprimé."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Impossible de supprimer le choix."));
}
?>
