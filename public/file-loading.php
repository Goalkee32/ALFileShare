<?php 
$_GET["class"] = "AuthController";
$_GET["method"] = "isLoggedIn";

require dirname(__DIR__) . "/controller/router-controller.php";

$success = $_SESSION["success"] ?? "";
$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"], $_SESSION["success"]);
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
    <link rel="stylesheet" href="../style.css">
    <title>Filuppladdning och Mapphantering</title>
</head>
<body>
<section class="w3-container w3-center" style="max-width:600px">
    <h2 class="w3-wide">AL File Share</h2>
    <p class="w3-opacity"><i>Jag älskar google ;></i></p>
</section>
<main>
    <nav class="w3-bar w3-black">
        <a href="../public/logged-in.php" class="w3-button w3-bar-item">Till dina mappar</a>
    </nav>
    <section class="w3-container">
        <h2>Filuppladdning och Mapphantering</h2>
        <?php include dirname(__DIR__) . "/view/success-view.php"; ?>
        <?php include dirname(__DIR__) . "/view/error-view.php"; ?>
        <!-- Formulär för att skapa mapp -->
        <form action="" method="post">
            <input type="text" name="newFolderName" placeholder="Ange nytt mappnamn" required>
            <input type="submit" name="createFolder" value="Skapa mapp">
        </form>

        <!-- Formulär för att ladda upp fil -->
        <form action="../controller/router-controller.php?class=FileController&method=uploadFile" method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="file" required>
            <button type="submit">Ladda upp fil</button>
        </form>

        <!-- Lista filer och mappar -->
        <?php include dirname(__DIR__) . "/view/file-list-view.php"; ?>
    </section>
</main>
</body>
</html>
