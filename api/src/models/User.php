<?php
class User {
    private $connexion;
    private $table_name = "Users";

    public $UserID;
    public $Email;
    public $Password;
    public $FirstName;
    public $LastName;


    public function __construct($db) {
        $this->connexion = $db;
    }

    // Création d'un utilisateur
    public function create() {
        $sql = "INSERT INTO " . $this->table_name . " (Email, Password, FirstName, LastName) VALUES (:Email, :Password, :FirstName, :LastName)";
        $query = $this->connexion->prepare($sql);
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $this->Password = htmlspecialchars(strip_tags($this->Password));
        $this->FirstName = htmlspecialchars(strip_tags($this->FirstName));
        $this->LastName = htmlspecialchars(strip_tags($this->LastName));
        $query->bindParam(":Email", $this->Email);
        $query->bindParam(":Password", $this->Password);
        $query->bindParam(":FirstName", $this->FirstName);
        $query->bindParam(":LastName", $this->LastName);
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
        $this->FirstName = $row['FirstName'];
        $this->LastName = $row['LastName'];
    }

    // Mise à jour d'un utilisateur
    public function update() {
        $sql = "UPDATE " . $this->table_name . " SET Email = :Email, Password = :Password, FirstName = :FirstName, LastName = :LastName WHERE UserID = :UserID";
        $query = $this->connexion->prepare($sql);
        $this->UserID = htmlspecialchars(strip_tags($this->UserID));
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $this->Password = htmlspecialchars(strip_tags($this->Password));
        $this->FirstName = htmlspecialchars(strip_tags($this->FirstName));
        $this->LastName = htmlspecialchars(strip_tags($this->LastName));
        $query->bindParam(':Email', $this->Email);
        $query->bindParam(':Password', $this->Password);
        $query->bindParam(':FirstName', $this->FirstName);
        $query->bindParam(':LastName', $this->LastName);
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

    // Connexion d'un utilisateur
    public function login() {
        session_start(); // Démarrer la session

        $sql = "SELECT UserID, Email, Password FROM " . $this->table_name . " WHERE Email = :Email LIMIT 0,1";
        $query = $this->connexion->prepare($sql);
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $query->bindParam(":Email", $this->Email);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        
        if($row && password_verify($this->Password, $row['Password'])) {
            $_SESSION['user_id'] = $row['UserID']; // Stocker l'ID utilisateur en session
            $_SESSION['name'] = $row['Name']; // Assuming 'Name' is a column in your users table
            $_SESSION['first_name'] = $row['FirstName']; // Assuming 'FirstName' is a column
        

            // Le mot de passe est correct, vous pouvez configurer les données de session ici
            $this->UserID = $row['UserID'];

            return true;
        }
        return false;
    }
}
