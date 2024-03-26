<?php
class Answer {
    private $connexion;
    private $table_name = "Answers";

    public $AnswerID;
    public $UserID;
    public $QuestionID;
    public $ChoiceID;

    public function __construct($db) {
        $this->connexion = $db;
    }

    // Création d'une réponse
    public function create() {
        $sql = "INSERT INTO " . $this->table_name . " (UserID, QuestionID, ChoiceID) VALUES (:UserID, :QuestionID, :ChoiceID)";
        $query = $this->connexion->prepare($sql);
        $this->UserID = htmlspecialchars(strip_tags($this->UserID));
        $this->QuestionID = htmlspecialchars(strip_tags($this->QuestionID));
        $this->ChoiceID = htmlspecialchars(strip_tags($this->ChoiceID));
        $query->bindParam(":UserID", $this->UserID);
        $query->bindParam(":QuestionID", $this->QuestionID);
        $query->bindParam(":ChoiceID", $this->ChoiceID);
        if($query->execute()) {
            return true;
        }
        return false;
    }

    // Lecture de toutes les réponses
    public function read() {
        $sql = "SELECT * FROM " . $this->table_name;
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }

    // Lecture d'une réponse spécifique par ID
    public function readOne() {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE AnswerID = ? LIMIT 0,1";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(1, $this->AnswerID);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $this->UserID = $row['UserID'];
        $this->QuestionID = $row['QuestionID'];
        $this->ChoiceID = $row['ChoiceID'];
    }

    // Mise à jour d'une réponse
    public function update() {
        $sql = "UPDATE " . $this->table_name . " SET UserID = :UserID, QuestionID = :QuestionID, ChoiceID = :ChoiceID WHERE AnswerID = :AnswerID";
        $query = $this->connexion->prepare($sql);
        $this->AnswerID = htmlspecialchars(strip_tags($this->AnswerID));
        $this->UserID = htmlspecialchars(strip_tags($this->UserID));
        $this->QuestionID = htmlspecialchars(strip_tags($this->QuestionID));
        $this->ChoiceID = htmlspecialchars(strip_tags($this->ChoiceID));
        $query->bindParam(':UserID', $this->UserID);
        $query->bindParam(':QuestionID', $this->QuestionID);
        $query->bindParam(':ChoiceID', $this->ChoiceID);
        $query->bindParam(':AnswerID', $this->AnswerID);
        if($query->execute()) {
            return true;
        }
        return false;
    }

    // Suppression d'une réponse
    public function delete() {
        $sql = "DELETE FROM " . $this->table_name . " WHERE AnswerID = ?";
        $query = $this->connexion->prepare($sql);
        $this->AnswerID = htmlspecialchars(strip_tags($this->AnswerID));
        $query->bindParam(1, $this->AnswerID);
        if($query->execute()) {
            return true;
        }
        return false;
    }
}
