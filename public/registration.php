<?php
session_start();
$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registera dig</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="login-container">
        <h2 class="logga-in">Registera dig</h2>
        <form action="../controller/router-controller.php?class=AuthController&method=registration" method="POST">
            <div class="form-group">
                <label for="username">Användarnamn</label>
                <input class="loginbox" type="text" id="username" name="username" placeholder="Ange ditt användarnamn" required>
            </div>
            <div class="form-group">
                <label for="password">Lösenord</label>
                <input class="loginbox" type="password" id="password" name="password" placeholder="Ange ditt lösenord" required>
            </div>
            <div class="form-group">
                <label for="password">Bekräfta lösenordet</label>
                <input class="loginbox" type="password" id="conf_password" name="conf_password" placeholder="Repetera ditt lösenord" required>
            </div>
            <div class="form-group">
                <a href="/public/login.php">Har du redan ett konto?</a>
                <button type="submit" class="login-btn">Registrera dig</button>
            </div>
        </form>
        <?php include dirname(__DIR__) . "../view/error-view.php"; ?>
    </div>
</body>
</html>