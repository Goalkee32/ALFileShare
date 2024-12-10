<?php
session_start();
require dirname(__DIR__) . "../.config/db-config.php";
require dirname(__DIR__) . "../model/user-model.php";

class AuthController {
    private $userModel;

    public function __construct() {
        $Server = new Server(); // Create instance of Server class
        $db = $Server->getConnection(); // Get PDO connection
        $this->userModel = new UserModel($db); // Create instance of UserModel
    }

    // Handle login request
    public function login() {
        $error = "";  // Store error message

        // Check form submition via POST
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = $_POST["username"] ?? "";
            $password = $_POST["password"] ?? "";

            // Attempt authenticate user
            $user = $this->userModel->authlogform($username, $password);

            if ($user) {
                // User authenticated
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                header("Location: ../public/logged-in.php");
                die();
            } else {
                // Authentication failed
                $_SESSION["error"] = "Invalid username or password!";
                header("Location: ../public/login.php");
                die();
            }
        }
    }   

    // Registration function
    public function registration() {
        $error = "";  // Store error message

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = $_POST["username"] ?? "";
            $password = $_POST["password"] ?? "";
            $conf_password = $_POST["conf_password"] ?? "";

            // Basic validation
            if ($password !== $conf_password) {
                $_SESSION["error"] = "Passwords do not match!";
                header("Location: ../public/registration.php");
                die();
            }

            // Check if username exists
            if ($this->userModel->userExists($username)) {
                $_SESSION["error"] = "Username already taken!";
                header("Location: ../public/registration.php");
                die();
            }

            // Hash
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Store user
            $this->userModel->createUser($username, $hashedPassword);

            // Redirect
            $_SESSION["success"] = "Registration successful! Please log in.";
            header("Location: ../public/login.php");
            die();
        }
    }
}


