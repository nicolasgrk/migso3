<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/config.php';
include_once '../../models/Compatibility.php';

$database = new Database();
$db = $database->getConnection();

if (isset($_GET['userID'])) {
    $currentUserID = $_GET['userID'];

    // Modification de la requête pour inclure les noms des autres utilisateurs
    $query = "SELECT c.UserID1, c.UserID2, c.CompatibilityPercent, u.FirstName AS OtherUserFirstName, u.LastName AS OtherUserLastName 
        FROM compatibility c
        LEFT JOIN users u ON u.UserID = IF(c.UserID1 = :currentUserID, c.UserID2, c.UserID1)
        WHERE c.UserID1 = :currentUserID OR c.UserID2 = :currentUserID";

    $stmt = $db->prepare($query);
    $stmt->bindParam(":currentUserID", $currentUserID);
    $stmt->execute();

    $compatibilityScores = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $compatibilityItem = array(
            "OtherUserID" => $currentUserID == $row['UserID1'] ? $row['UserID2'] : $row['UserID1'],
            "OtherUserFirstName" => $row['OtherUserFirstName'],
            "OtherUserLastName" => $row['OtherUserLastName'],
            "CompatibilityPercent" => $row['CompatibilityPercent']
        );
        $compatibilityScores[] = $compatibilityItem;
    }

    echo json_encode($compatibilityScores);
} else {
    http_response_code(400);
    echo json_encode(array("message" => "L'ID de l'utilisateur n'est pas spécifié dans l'URL."));
}

?>
