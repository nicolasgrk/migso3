<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['UserID'])) {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        $user->UserID = $_GET['UserID'];
        $user->readOne();

        if ($user->Email != null) {
            $user_arr = array(
                "UserID" => $user->UserID,
                "Email" => $user->Email,
                "FirstName" => $FirstName,
                "LastName" => $LastName,
                "Password" => $user->Password
            );

            http_response_code(200);
            echo json_encode($user_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Utilisateur non trouvé."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "UserID manquant."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
