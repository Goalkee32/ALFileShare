<!DOCTYPE html>
<html lang="en">
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
    <p class="w3-opacity"><i>I hate google</i></p>
</section>

<main>
    <nav class="w3-bar w3-black">
        <a href="../public/logged-in.php" class="w3-button w3-bar-item">Till dina mappar</a>
    </nav>

    <nav>
        <h2>Filuppladdning och Mapphantering</h2>

        <?php
        $baseDir = 'media';
        $currentDir = realpath($baseDir);
        if (isset($_GET['dir'])) {
            $requestedDir = realpath($baseDir . '/' . basename($_GET['dir']));
            if ($requestedDir && strpos($requestedDir, realpath($baseDir)) === 0) {
                $currentDir = $requestedDir;
            }
        }
        echo "<h3>Nuvarande mapp: " . htmlspecialchars(str_replace(realpath($baseDir), '', $currentDir)) . "</h3>";
        ?>

        <!-- Skapa ny mapp -->
        <form action="upload.php" method="post">
            <input type="text" name="newFolderName" placeholder="Ange nytt mappnamn" required>
            <input type="hidden" name="currentDir" value="<?php echo htmlspecialchars($currentDir); ?>">
            <input type="submit" name="createFolder" value="Skapa mapp">
        </form>

        <!-- Ladda upp fil -->
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="uploadedFile" required>
            <input type="hidden" name="currentDir" value="<?php echo htmlspecialchars($currentDir); ?>">
            <input type="submit" value="Ladda upp fil">
        </form>

        <!-- Lista filer och mappar -->
        <?php
        $items = scandir($currentDir);
        echo "<ul>";
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $itemPath = $currentDir . '/' . $item;
            $relativePath = str_replace(realpath($baseDir), '', $itemPath);

            if (is_dir($itemPath)) {
                echo "<li><a href='?dir=" . urlencode(basename($relativePath)) . "'>[Mapp] " . htmlspecialchars($item) . "</a></li>";
            } else {
                echo "<li>" . htmlspecialchars($item) . "</li>";
            }
        }
        echo "</ul>";
        ?>
    </nav>
</main>
</body>
</html>
