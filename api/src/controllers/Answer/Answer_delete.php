<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/Answer.php';

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $database = new Database();
    $db = $database->getConnection();
    $answer = new Answer($db);

    if (!empty($data['AnswerID'])) {
        $answer->AnswerID = $data['AnswerID'];

        if ($answer->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "La réponse a été supprimée."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de supprimer la réponse."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "AnswerID manquant pour la suppression."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
