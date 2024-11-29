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
        <a href="../upload.php" class="w3-button w3-bar-item">Ladda upp</a>
        <?php
        $dir = 'media';

        if (is_dir($dir) && $handle = opendir($dir)) {
            while (($folder = readdir($handle)) !== false) {
                if ($folder != '.' && $folder != '..' && is_dir($dir . '/' . $folder)) {
                    echo '<a href="?dir=' . urlencode($folder) . '" class="w3-button w3-bar-item">' . htmlspecialchars($folder) . '</a>';
                }
            }
            closedir($handle);
        }
        ?>
    </nav>

    <nav>
        <h2>Filuppladdning och Mapphantering</h2>

        <?php
        $baseDir = 'media/';
        $currentDir = $baseDir;

        if (isset($_GET['dir'])) {
            $currentDir = realpath($baseDir . '/' . $_GET['dir']);
            if (strpos($currentDir, realpath($baseDir)) !== 0) {
                $currentDir = $baseDir;
            }
        }

        echo "<h3>Nuvarande mapp: " . htmlspecialchars(str_replace(realpath($baseDir), '', $currentDir)) . "</h3>";
        ?>

        <form action="upload.php" method="post">
            <input type="text" name="newFolderName" placeholder="Ange nytt mappnamn" required>
            <input type="hidden" name="currentDir" value="<?php echo htmlspecialchars($currentDir); ?>">
            <input type="submit" name="createFolder" value="Skapa mapp">
        </form>

        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="uploadedFile" required>
            <input type="hidden" name="currentDir" value="<?php echo htmlspecialchars($currentDir); ?>">
            <input type="submit" value="Ladda upp fil">
        </form>

        <?php
        $items = scandir($currentDir);
        echo "<ul>";
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;

            $itemPath = $currentDir . '/' . $item;
            $relativePath = str_replace(realpath($baseDir), '', realpath($itemPath));

            if (is_dir($itemPath)) {
                echo "<li><a href='?dir=" . urlencode($relativePath) . "'>[Mapp] " . htmlspecialchars($item) . "</a></li>";
            } else {
                echo "<li>" . htmlspecialchars($item) . "</li>";
            }
        }
        echo "</ul>";

        // Visa bilder
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $images = array_filter($items, function($item) use ($imageExtensions, $currentDir) {
            $ext = pathinfo($item, PATHINFO_EXTENSION);
            return in_array(strtolower($ext), $imageExtensions) && is_file($currentDir . '/' . $item);
        });

        if (!empty($images)) {
            echo '<div class="w3-row-padding">';
            foreach ($images as $image) {
                $imagePath = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', realpath($currentDir . '/' . $image));
                echo '<div class="w3-col s4">';
                echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($image) . '" style="width:100%" class="w3-hover-opacity">';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo "<p>Inga bilder hittades i denna mapp.</p>";
        }
        ?>
    </nav>
</main>
</body>
</html>
