<?php

class UserModel {
    private $db;

    // Constructor accepts PDO connection
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Aauthenticate the User
    public function authlogform($username, $password) {
        $sql = "SELECT * FROM accounts WHERE username_account = :username_account LIMIT 1";
        $stmt = $this->db->prepare($sql); // Prepare the PDO statement
        $stmt->execute(["username_account" => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if (password_verify($password, $user["psw_account"])) {
            return $user; // Return user data
        }

        return false; // Authentication fails
    }

    // Check if a username already exists
    public function userExists($username) {
        $sql = "SELECT * FROM accounts WHERE username_account = :username_account LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["username_account" => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Create a new user
    public function createUser($username, $hashedPassword) {
        $sql = "INSERT INTO accounts (username_account, psw_account) VALUES (:username_account, :psw_account)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["username_account" => $username, "psw_account" => $hashedPassword]);
    }
}