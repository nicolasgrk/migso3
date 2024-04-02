<?php
// En-tête requis pour la prise en charge de CORS et le type de contenu JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Inclure les fichiers de configuration et de modèle nécessaires
include_once '../../config/config.php';
include_once '../../models/User.php'; // Assurez-vous que le chemin est correct

// Vérification de la méthode de la requête
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // Instanciation de la base de données et de l'utilisateur
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    // Lecture des utilisateurs
    $stmt = $user->read();
    $num = $stmt->rowCount();

    // Vérification s'il y a plus de 0 enregistrement
    if ($num > 0) {

        // Tableau des utilisateurs
        $users_arr = array();
        $users_arr["data"] = array();

        // Récupération du contenu de la table
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $user_item = array(
                "UserID" => $UserID,
                "Email" => $Email,
                "FirstName" => $FirstName,
                "LastName" => $LastName,
                // Vous pouvez inclure d'autres champs ici
            );

            array_push($users_arr["data"], $user_item);
        }

        // Réponse 200 OK
        http_response_code(200);

        // Affichage des données des utilisateurs en JSON
        echo json_encode($users_arr);
    } else {
        // Réponse 404 Not Found
        http_response_code(404);

        // Aucun utilisateur trouvé
        echo json_encode(array("message" => "Aucun utilisateur trouvé."));
    }
} else {
    // Réponse 405 Method Not Allowed
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
