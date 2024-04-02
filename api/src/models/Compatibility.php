<?php

class Compatibility {
    // Connexion à la base de données et nom de la table
    private $conn;
    private $table_name = "compatibility";

    // Propriétés de l'objet
    public $CompID;
    public $UserID1;
    public $UserID2;
    public $CompatibilityPercent;

    // Constructeur avec $db comme connexion à la base de données
    public function __construct($db) {
        $this->conn = $db;
    }

    // Fonction pour créer un enregistrement de compatibilité
    function create() {
        // Requête d'insertion
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                    UserID1=:UserID1,
                    UserID2=:UserID2,
                    CompatibilityPercent=:CompatibilityPercent";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->UserID1 = htmlspecialchars(strip_tags($this->UserID1));
        $this->UserID2 = htmlspecialchars(strip_tags($this->UserID2));
        $this->CompatibilityPercent = htmlspecialchars(strip_tags($this->CompatibilityPercent));

        // Liaison des valeurs
        $stmt->bindParam(":UserID1", $this->UserID1);
        $stmt->bindParam(":UserID2", $this->UserID2);
        $stmt->bindParam(":CompatibilityPercent", $this->CompatibilityPercent);

        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Fonction pour récupérer la compatibilité entre deux utilisateurs
    function getCompatibility($userID1, $userID2) {
        // Requête de sélection
        $query = "SELECT CompatibilityPercent
                  FROM " . $this->table_name . "
                  WHERE (UserID1 = :UserID1 AND UserID2 = :UserID2) OR (UserID1 = :UserID2 AND UserID2 = :UserID1)";

        // Préparation de la requête
        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $userID1 = htmlspecialchars(strip_tags($userID1));
        $userID2 = htmlspecialchars(strip_tags($userID2));

        // Liaison des valeurs
        $stmt->bindParam(":UserID1", $userID1);
        $stmt->bindParam(":UserID2", $userID2);

        // Exécution de la requête
        $stmt->execute();

        return $stmt;
    }
}
