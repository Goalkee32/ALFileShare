<?php

class UserModel {
    private $db;

    // Konstruktor accepterar PDO koppling
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Autorisera användaren
    public function authlogform($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($sql); // Förbered PDO utryck
        $stmt->execute(["username" => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifiera lösenordet
        if (password_verify($password, $user["psw"])) {
            return $user; // Return user data
        }

        return false; // Authentication fails
    }

    // Spara token mot användarens id
    public function rememberToken($token, $user_id) {
        $sql = "UPDATE users SET remember_token = :token WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["token" => $token, "user_id" => $user_id]);
    }

    // Kollar om remember_me token existerar mot databasen.
    // BÖR GÖRA SÄKRARE, T.EX KOLLA TOKEN MOT USER_ID
    public function tokenActive($token) {
        $sql = "SELECT * FROM users WHERE remember_token = :token LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;
    }

    // Kollar om användarnamnet existerar
    public function userExists($username) {
        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["username" => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Skapa en ny användare
    public function createUser($username, $hashedPassword) {
        $sql = "INSERT INTO users (username, psw) VALUES (:username, :psw)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["username" => $username, "psw" => $hashedPassword]);
    }

    // Logga ut funktion
    public function logoutFetch($user_id) {
        $sql = "UPDATE users SET remember_token = NULL WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["user_id" => $user_id]);
    }
}