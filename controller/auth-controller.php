<?php
session_start();
require dirname(__DIR__) . "../.config/db-config.php";
require dirname(__DIR__) . "../model/user-model.php";

class AuthController {
    private $userModel;

    public function __construct() {
        $Server = new Server(); // Skapar instans av Server
        $db = $Server->getConnection(); // Gör PDO koppling
        $this->userModel = new UserModel($db); // Skapar instans av UserModel
    }

    // Tar hand om login förfrågan
    public function login() {
        $error = "";  // Sparar error meddelande

        // Kollar om form var skickad
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = $_POST["username"] ?? "";
            $password = $_POST["password"] ?? "";

            // Försöker autorisera användaren
            $user = $this->userModel->authlogform($username, $password);

            if ($user) {
                // Användaren autoriserad
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["username"] = $user["username_account"];

                if (isset($_POST["remember_me"]) && $_POST["remember_me"] == "on") {
                    // Generera/spara token
                    $token = bin2hex(random_bytes(32));
                    setcookie("remember_me", $token, time() + (5 * 24 * 60 * 60), "/");

                    $user_id = $user["user_id"];
                    $this->userModel->rememberToken($token, $user_id);
                }

                header("Location: ../public/logged-in.php");
                die();
            } else {
                // Autorisering misslyckades
                $_SESSION["error"] = "Invalid username or password!";
                header("Location: ../public/login.php");
                die();
            }
        }
    }   

    // Registrering funktion
    public function registration() {
        $error = "";  // Sparar error meddelande

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = $_POST["username"] ?? "";
            $password = $_POST["password"] ?? "";
            $conf_password = $_POST["conf_password"] ?? "";

            // Enkel godkännande
            if ($password !== $conf_password) {
                $_SESSION["error"] = "Passwords do not match!";
                header("Location: ../public/registration.php");
                die();
            }

            // Kollar om användarnamnet används redan
            if ($this->userModel->userExists($username)) {
                $_SESSION["error"] = "Username already taken!";
                header("Location: ../public/registration.php");
                die();
            }

            // Hash & sparar
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->userModel->createUser($username, $hashedPassword);

            $_SESSION["success"] = "Registration successful! Please log in.";
            header("Location: ../public/login.php");
            die();
        }
    }

    // Kollar om användaren redan är inloggad
    public function isLoggedIn() {
        if (isset($_SESSION['user_id'])) {
        } elseif (isset($_COOKIE["remember_me"])) {
            $token = $_COOKIE['remember_me'];
            $user = $this->userModel->tokenActive($token);

            if ($user) {
                // Token är korrekt, återinitiera information
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["username"] = $user["username_accounts"];
            } else {
                // Oklar token, rensar kakor
                setcookie("remember_me", "", time() - 3600, "/");
            }
        } else {
            // Användaren är inte inloggad, skickar hen till main sida
            header("Location: ../public/main.php");
            die();
        }
    }

    // Funktion för utloggande av användarna
    public function logout() {
        session_start();
        $user_id = $_SESSION["user_id"];
        session_unset();
        session_destroy();
        
        // Rensar kakor
        setcookie("remember_me", "", time() - 3600, "/");
        $this->userModel->logoutFetch($user_id);

        header("Location: ../public/login.php");
        die();
    }
}


