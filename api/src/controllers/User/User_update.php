<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->UserID) && (!empty($data->Email) || !empty($data->Password))) {
        $user->UserID = $data->UserID;
        $user->Email = !empty($data->Email) ? $data->Email : null;
        $user->Password = !empty($data->Password) ? password_hash($data->Password, PASSWORD_DEFAULT) : null;

        if ($user->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "L'utilisateur a été mis à jour."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de mettre à jour l'utilisateur."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Données incomplètes pour la mise à jour."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
