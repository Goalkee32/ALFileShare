<?php

class UserModel {
    private $db;

    // Konstruktor accepterar PDO koppling
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Autorisera användaren
    public function authlogform($username, $password) {
        $sql = "SELECT * FROM accounts WHERE username_account = :username_account LIMIT 1";
        $stmt = $this->db->prepare($sql); // Förbered PDO utryck
        $stmt->execute(["username_account" => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifiera lösenordet
        if (password_verify($password, $user["psw_account"])) {
            return $user; // Return user data
        }

        return false; // Authentication fails
    }

    // Spara token mot användarens id
    public function rememberToken($token, $user_id) {
        $query = "UPDATE accounts SET remember_token = :token WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(["token" => $token, "user_id" => $user_id]);
    }

    // Kollar om remember_me token existerar mot databasen.
    // BÖR GÖRA SÄKRARE, T.EX KOLLA TOKEN MOT USER_ID
    public function tokenActive($token) {
        $query = "SELECT * FROM accounts WHERE remember_token = :token LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;
    }

    // Kollar om användarnamnet existerar
    public function userExists($username) {
        $sql = "SELECT * FROM accounts WHERE username_account = :username_account LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["username_account" => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Skapa en ny användare
    public function createUser($username, $hashedPassword) {
        $sql = "INSERT INTO accounts (username_account, psw_account) VALUES (:username_account, :psw_account)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["username_account" => $username, "psw_account" => $hashedPassword]);
    }

    // Logga ut funktion
    public function logoutFetch($user_id) {
        $query = "UPDATE accounts SET remember_token = NULL WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(["user_id" => $user_id]);
    }
}