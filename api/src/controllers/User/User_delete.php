<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    if (!empty($data['UserID'])) {
        $user->UserID = $data['UserID'];

        if ($user->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "L'utilisateur a été supprimé."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de supprimer l'utilisateur."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "UserID manquant pour la suppression."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
