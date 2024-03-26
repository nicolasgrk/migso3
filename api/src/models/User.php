<?php
class User {
    private $connexion;
    private $table_name = "Users";

    public $UserID;
    public $Email;
    public $Password;

    public function __construct($db) {
        $this->connexion = $db;
    }

    // Création d'un utilisateur
    public function create() {
        $sql = "INSERT INTO " . $this->table_name . " (Email, Password) VALUES (:Email, :Password)";
        $query = $this->connexion->prepare($sql);
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $this->Password = htmlspecialchars(strip_tags($this->Password));
        $query->bindParam(":Email", $this->Email);
        $query->bindParam(":Password", $this->Password);
        if($query->execute()) {
            return true;
        }
        return false;
    }

    // Lecture de tous les utilisateurs
    public function read() {
        $sql = "SELECT * FROM " . $this->table_name;
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }

    // Lecture d'un utilisateur spécifique par ID
    public function readOne() {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE UserID = ? LIMIT 0,1";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(1, $this->UserID);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $this->Email = $row['Email'];
        $this->Password = $row['Password'];
    }

    // Mise à jour d'un utilisateur
    public function update() {
        $sql = "UPDATE " . $this->table_name . " SET Email = :Email, Password = :Password WHERE UserID = :UserID";
        $query = $this->connexion->prepare($sql);
        $this->UserID = htmlspecialchars(strip_tags($this->UserID));
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $this->Password = htmlspecialchars(strip_tags($this->Password));
        $query->bindParam(':Email', $this->Email);
        $query->bindParam(':Password', $this->Password);
        $query->bindParam(':UserID', $this->UserID);
        if($query->execute()) {
            return true;
        }
        return false;
    }

    // Suppression d'un utilisateur
    public function delete() {
        $sql = "DELETE FROM " . $this->table_name . " WHERE UserID = ?";
        $query = $this->connexion->prepare($sql);
        $this->UserID = htmlspecialchars(strip_tags($this->UserID));
        $query->bindParam(1, $this->UserID);
        if($query->execute()) {
            return true;
        }
        return false;
    }
}
