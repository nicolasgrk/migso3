<?php
// En-tête requis pour la prise en charge de CORS et le type de contenu JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Inclure les fichiers de configuration et de modèle nécessaires
include_once '../../config/config.php';
include_once '../../models/User.php'; // Assurez-vous que le chemin est correct

// Vérification de la méthode de la requête
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Instanciation de la base de données et de l'utilisateur
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    // Récupération des données envoyées
    $data = json_decode(file_get_contents("php://input"));

    // Vérification des données requises
    if (!empty($data->Email) && !empty($data->Password)) {

        // Association des données à l'objet utilisateur
        $user->Email = $data->Email;
        // Ici, vous devez hasher le mot de passe avant de le stocker
        $user->Password = password_hash($data->Password, PASSWORD_DEFAULT);

        // Tentative de création de l'utilisateur
        if ($user->create()) {
            // Réponse 201 Created
            http_response_code(201);
            echo json_encode(array("message" => "L'utilisateur a été créé avec succès."));
        } else {
            // Réponse 503 Service Unavailable
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de créer l'utilisateur."));
        }
    } else {
        // Réponse 400 Bad Request
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de créer l'utilisateur. Des données sont manquantes."));
    }
} else {
    // Réponse 405 Method Not Allowed
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
