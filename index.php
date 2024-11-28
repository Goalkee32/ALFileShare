<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filuppladdning och Mapphantering</title>
</head>
<body>
    <h2>Filuppladdning och Mapphantering</h2>

    <?php
    // Standardmapp om ingen kategori är vald
    $baseDir = 'media/';
    $currentDir = $baseDir;

    // Kontrollera om användaren navigerar i en specifik mapp
    if (isset($_GET['dir'])) {
        // Hantera relativ sökväg för navigering
        $currentDir = realpath($baseDir . $_GET['dir']);
        
        // Säkerställ att vi inte tillåter navigering utanför basmappen
        if (strpos($currentDir, realpath($baseDir)) !== 0) {
            $currentDir = $baseDir;
        }
    }

    echo "<h3>Nuvarande mapp: " . htmlspecialchars(str_replace($baseDir, '', $currentDir)) . "</h3>";
    ?>

    <!-- Formulär för att skapa ny mapp -->
    <form action="index.php" method="post">
        <input type="text" name="newFolderName" placeholder="Ange nytt mappnamn" required>
        <input type="hidden" name="currentDir" value="<?php echo htmlspecialchars($currentDir); ?>">
        <input type="submit" name="createFolder" value="Skapa mapp">
    </form>

    <!-- Formulär för att ladda upp filer -->
    <form action="index.php" method="post" enctype="multipart/form-data">
        <input type="file" name="uploadedFile" required>
        <input type="hidden" name="currentDir" value="<?php echo htmlspecialchars($currentDir); ?>">
        <input type="submit" value="Ladda upp fil">
    </form>

    <?php
    // Hantera mappskapande
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createFolder'])) {
        $newFolderName = $_POST['newFolderName'];
        $newFolderPath = $_POST['currentDir'] . '/' . $newFolderName;

        // Skapa mappen om den inte finns
        if (!is_dir($newFolderPath)) {
            mkdir($newFolderPath, 0777, true);
            echo "<p>Mappen <strong>" . htmlspecialchars($newFolderName) . "</strong> skapades.</p>";
        } else {
            echo "<p>Mappen finns redan.</p>";
        }
    }

    // Hantera filuppladdning
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploadedFile'])) {
        $uploadFile = $_POST['currentDir'] . '/' . basename($_FILES['uploadedFile']['name']);

        // Flytta filen till den angivna mappen
        if (move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $uploadFile)) {
            echo "<p>Filen laddades upp som: " . htmlspecialchars(basename($_FILES['uploadedFile']['name'])) . "</p>";
        } else {
            echo "<p>Ett fel uppstod vid uppladdning av filen.</p>";
        }
    }

    // Visa mappar och filer i den aktuella mappen
    $items = scandir($currentDir);
    echo "<ul>";
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $itemPath = $currentDir . '/' . $item;
        if (is_dir($itemPath)) {
            // Lägg till länk till mappen om det är en mapp
            $relativePath = str_replace($baseDir, '', $itemPath);
            echo "<li><a href='?dir=" . urlencode($relativePath) . "'>[Mapp] " . htmlspecialchars($item) . "</a></li>";
        } else {
            // Visa filnamn om det är en fil
            echo "<li>" . htmlspecialchars($item) . "</li>";
        }
    }
    echo "</ul>";
    ?>
</body>
</html>
