<?php

include_once '../../config/config.php';
include_once '../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->Email) && !empty($data->Password)) {
        $user->Email = $data->Email;
        $user->Password = $data->Password; // Ici, vous vérifiez les identifiants

        if ($user->login()) {
            // Utilisation de readOne pour récupérer les détails de l'utilisateur
            $user->readOne(); // Assurez-vous que cette méthode met à jour les propriétés de l'objet $user
            
            // Vérifiez que vous avez bien les informations nécessaires
            if ($user->FirstName != null && $user->LastName != null) {
                http_response_code(200);
                echo json_encode(array(
                    "message" => "Connexion réussie.",
                    "UserID" => $user->UserID,
                    "Email" => $user->Email,
                    "FirstName" => $user->FirstName,
                    "LastName" => $user->LastName
                ));
            } else {
                // Gérer le cas où les informations supplémentaires ne sont pas disponibles
                http_response_code(404);
                echo json_encode(array("message" => "Détails de l'utilisateur non trouvés."));
            }
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(array("message" => "Email ou mot de passe incorrect."));
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(array("message" => "Email et mot de passe requis."));
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
