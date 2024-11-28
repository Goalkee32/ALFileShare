<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logga in</title>
    <link rel="stylesheet" href="../style.css">
    <?php require dirname(__DIR__) . "../includes/header.php"; ?>
</head>
<body>
    <div class="login-container">
        <h2 class="logga-in">Logga in</h2>
        <form id="loginForm">
            <div class="form-group">
                <label for="username">Användarnamn</label>
                <input class="loginbox" type="text" id="username" name="username" placeholder="Ange ditt användarnamn" required>
            </div>
            <div class="form-group">
                <label for="password">Lösenord</label>
                <input class="loginbox" type="password" id="password" name="password" placeholder="Ange ditt lösenord" required>
            </div>
            <button type="submit" class="login-btn">Logga in</button>
        </form>
    </div>
</body>
<script src="../assets/js/fetch.js"></script>
</html>