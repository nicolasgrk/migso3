<?php
class Choice {
    // Connexion à la base de données et nom de la table
    private $connexion;
    private $table_name = "Choices";

    // Propriétés de l'objet correspondant aux colonnes de la table
    public $ChoiceID;
    public $QuestionID;
    public $ChoiceText;

    // Constructeur avec $db comme connexion à la base de données
    public function __construct($db) {
        $this->connexion = $db;
    }

    // Méthodes CRUD

    // Créer un nouveau choix
    public function create() {
        $sql = "INSERT INTO " . $this->table_name . " (QuestionID, ChoiceText) VALUES (:QuestionID, :ChoiceText)";
        $query = $this->connexion->prepare($sql);

        // Nettoyage
        $this->QuestionID=htmlspecialchars(strip_tags($this->QuestionID));
        $this->ChoiceText=htmlspecialchars(strip_tags($this->ChoiceText));

        // Liaison
        $query->bindParam(":QuestionID", $this->QuestionID);
        $query->bindParam(":ChoiceText", $this->ChoiceText);

        if($query->execute()) {
            return true;
        }
        return false;
    }

    // Lire tous les choix
    public function read() {
        $sql = "SELECT * FROM " . $this->table_name;
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }

    // Lire un choix spécifique par ID
    public function readOne() {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE ChoiceID = ? LIMIT 0,1";
        $query = $this->connexion->prepare($sql);

        // Liaison de l'ID du choix à récupérer
        $query->bindParam(1, $this->ChoiceID);

        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);

        // Affectation des valeurs à l'objet
        $this->QuestionID = $row['QuestionID'];
        $this->ChoiceText = $row['ChoiceText'];
    }

    // Mettre à jour un choix
    public function update() {
        $sql = "UPDATE " . $this->table_name . " SET QuestionID = :QuestionID, ChoiceText = :ChoiceText WHERE ChoiceID = :ChoiceID";
        $query = $this->connexion->prepare($sql);

        // Nettoyage
        $this->QuestionID=htmlspecialchars(strip_tags($this->QuestionID));
        $this->ChoiceText=htmlspecialchars(strip_tags($this->ChoiceText));
        $this->ChoiceID=htmlspecialchars(strip_tags($this->ChoiceID));

        // Liaison
        $query->bindParam(":QuestionID", $this->QuestionID);
        $query->bindParam(":ChoiceText", $this->ChoiceText);
        $query->bindParam(":ChoiceID", $this->ChoiceID);

        if($query->execute()) {
            return true;
        }
        return false;
    }

    // Supprimer un choix
    public function delete() {
        $sql = "DELETE FROM " . $this->table_name . " WHERE ChoiceID = ?";
        $query = $this->connexion->prepare($sql);

        // Nettoyage
        $this->ChoiceID=htmlspecialchars(strip_tags($this->ChoiceID));

        // Liaison
        $query->bindParam(1, $this->ChoiceID);

        if($query->execute()) {
            return true;
        }
        return false;
    }
}
