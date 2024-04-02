<?php
session_start(); // Démarrer la session
session_unset(); // Supprimer toutes les variables de session
session_destroy(); // Détruire la session

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
http_response_code(200);
echo json_encode(array("message" => "Déconnexion réussie."));
?>
