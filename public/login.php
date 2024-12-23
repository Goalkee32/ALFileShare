<?php
session_start();
$success = $_SESSION["success"] ?? "";
$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"], $_SESSION["success"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logga in</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="login-container">
        <h2 class="logga-in">Logga in</h2>
        <?php include dirname(__DIR__) . "../view/success-view.php"; ?>
        <form action="../controller/router-controller.php?class=AuthController&method=login" method="POST">
            <div class="form-group">
                <label for="username">Användarnamn</label>
                <input class="loginbox" type="text" id="username" name="username" placeholder="Ange ditt användarnamn" required>
            </div>
            <div class="form-group">
                <label for="password">Lösenord</label>
                <input class="loginbox" type="password" id="password" name="password" placeholder="Ange ditt lösenord" required>
            </div>
            <div class="form-group">
                <a href="/public/registration.php">Registerar dig idag!</a>
                <button type="submit" class="login-btn">Logga in</button>
            </div>
        </form>
        <?php include dirname(__DIR__) . "../view/login-error-view.php"; ?>
    </div>
</body>
</html>