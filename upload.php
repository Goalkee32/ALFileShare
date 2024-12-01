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

        <?php
        $baseDir = 'media';
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true); // Skapa huvudmappen om den inte finns
        }

        $currentDir = realpath($baseDir);

        if (isset($_GET['dir'])) {
            $requestedDir = realpath($baseDir . '/' . $_GET['dir']);
            if ($requestedDir && strpos($requestedDir, realpath($baseDir)) === 0) {
                $currentDir = $requestedDir;
            }
        }

        echo "<h3>Nuvarande mapp: " . htmlspecialchars(str_replace(realpath($baseDir), '', $currentDir)) . "</h3>";

        // Hantera skapande av mapp
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createFolder'])) {
            $newFolderName = trim($_POST['newFolderName']);
            $newFolderPath = $currentDir . '/' . basename($newFolderName);

            if (!is_dir($newFolderPath)) {
                if (mkdir($newFolderPath, 0777, true)) {
                    echo "<p>Mappen '$newFolderName' skapades framgångsrikt.</p>";
                } else {
                    echo "<p>Kunde inte skapa mappen '$newFolderName'.</p>";
                }
            } else {
                echo "<p>Mappen '$newFolderName' finns redan.</p>";
            }
        }

        // Hantera filuppladdning
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploadedFile'])) {
            $uploadFile = $_FILES['uploadedFile'];
            $targetFilePath = $currentDir . '/' . basename($uploadFile['name']);
            $uploadOk = 1;

            $allowedTypes = ['jpg', 'png', 'gif', 'pdf', 'txt', 'docx']; // Tillåtna filtyper
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            if (!in_array($fileType, $allowedTypes)) {
                echo "<p>Endast JPG, PNG, GIF, PDF, TXT och DOCX-filer är tillåtna.</p>";
                $uploadOk = 0;
            }

            if (file_exists($targetFilePath)) {
                echo "<p>Filen finns redan.</p>";
                $uploadOk = 0;
            }

            if ($uploadOk == 1) {
                if (move_uploaded_file($uploadFile['tmp_name'], $targetFilePath)) {
                    echo "<p>Filen " . htmlspecialchars($uploadFile['name']) . " har laddats upp.</p>";
                } else {
                    echo "<p>Ett fel uppstod vid uppladdningen.</p>";
                }
            }
        }
        ?>

        <!-- Formulär för att skapa mapp -->
        <form action="" method="post">
            <input type="text" name="newFolderName" placeholder="Ange nytt mappnamn" required>
            <input type="submit" name="createFolder" value="Skapa mapp">
        </form>

        <!-- Formulär för att ladda upp fil -->
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="uploadedFile" required>
            <input type="submit" value="Ladda upp fil">
        </form>

        <!-- Lista filer och mappar -->
        <h3>Innehåll:</h3>
        <ul>
            <?php
            $items = scandir($currentDir);
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                $itemPath = $currentDir . '/' . $item;
                $relativePath = str_replace(realpath($baseDir), '', $itemPath);

                if (is_dir($itemPath)) {
                    echo "<li><a href='?dir=" . urlencode(str_replace(realpath($baseDir) . '/', '', $itemPath)) . "'>[Mapp] " . htmlspecialchars($item) . "</a></li>";
                } else {
                    echo "<li>" . htmlspecialchars($item) . "</li>";
                }
            }
            ?>
        </ul>
    </section>
</main>
</body>
</html>
