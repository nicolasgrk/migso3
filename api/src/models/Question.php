<?php
class Question {
    private $connexion;
    private $table_name = "Questions";

    public $QuestionID;
    public $QuestionText;

    public function __construct($db) {
        $this->connexion = $db;
    }

    // Création d'une question
    public function create() {
        $sql = "INSERT INTO " . $this->table_name . " (QuestionText) VALUES (:QuestionText)";
        $query = $this->connexion->prepare($sql);
        $this->QuestionText = htmlspecialchars(strip_tags($this->QuestionText));
        $query->bindParam(":QuestionText", $this->QuestionText);
        if($query->execute()) {
            return true;
        }
        return false;
    }

    // Lecture de toutes les questions
    public function read() {
        $sql = "SELECT * FROM " . $this->table_name;
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }
       // Lecture de toutes les questions avec choix
       public function readWithChoice() {
        $query = "SELECT q.QuestionID, q.QuestionText, c.ChoiceID, c.ChoiceText 
                  FROM " . $this->table_name . " q
                  LEFT JOIN Choices c ON q.QuestionID = c.QuestionID";

        $stmt = $this->connexion->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lecture d'une question spécifique par ID
    public function readOne() {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE QuestionID = ? LIMIT 0,1";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(1, $this->QuestionID);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $this->QuestionText = $row['QuestionText'];
    }

    // Mise à jour d'une question
    public function update() {
        $sql = "UPDATE " . $this->table_name . " SET QuestionText = :QuestionText WHERE QuestionID = :QuestionID";
        $query = $this->connexion->prepare($sql);
        $this->QuestionID = htmlspecialchars(strip_tags($this->QuestionID));
        $this->QuestionText = htmlspecialchars(strip_tags($this->QuestionText));
        $query->bindParam(':QuestionText', $this->QuestionText);
        $query->bindParam(':QuestionID', $this->QuestionID);
        if($query->execute()) {
            return true;
        }
        return false;
    }

    // Suppression d'une question
    public function delete() {
        $sql = "DELETE FROM " . $this->table_name . " WHERE QuestionID = ?";
        $query = $this->connexion->prepare($sql);
        $this->QuestionID = htmlspecialchars(strip_tags($this->QuestionID));
        $query->bindParam(1, $this->QuestionID);
        if($query->execute()) {
            return true;
        }
        return false;
    }
}
