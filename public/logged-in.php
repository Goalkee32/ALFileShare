<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AL File Share</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<section class="w3-container w3-center" style="max-width:600px">
    <h2 class="w3-wide">AL File Share</h2>
    <p class="w3-opacity"><i>Jag älskar google ;></i></p>
</section>

<main>
    <nav class="w3-bar w3-black">
        <a href="../upload.php" class="w3-button w3-bar-item">Ladda upp</a>
        <?php
        // Ange sökvägen där mapparna finns
        $dir = '../media';

        // Kontrollera om katalogen finns och är läsbar
        if (is_dir($dir) && $handle = opendir($dir)) {
            while (($folder = readdir($handle)) !== false) {
                // Uteslut "." och ".." samt visa endast mappar
                if ($folder != '.' && $folder != '..' && is_dir($dir . '/' . $folder)) {
                    echo '<a href="?folder=' . urlencode($folder) . '" class="w3-button w3-bar-item">' . htmlspecialchars($folder) . '</a>';
                }
            }
            closedir($handle);
        }
        ?>
    </nav>

    <section class="w3-container">
        <?php
        if (isset($_GET['folder'])) {
            $selectedFolder = $dir . '/' . $_GET['folder'];

            if (is_dir($selectedFolder)) {
                echo '<h3>Bilder i mappen: ' . htmlspecialchars($_GET['folder']) . '</h3>';

                $images = glob($selectedFolder . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

                if ($images) {
                    echo '<div class="w3-row-padding">';
                    foreach ($images as $image) {
                        echo '<div class="w3-col s4">';
                        echo '<img src="' . $image . '" alt="' . basename($image) . '" style="width:100%" class="w3-hover-opacity">';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<p>Inga bilder hittades i denna mapp.</p>';
                }
            } else {
                echo '<p>Mappen kunde inte hittas.</p>';
            }
        }
        ?>
    </section>

</main>

</body>
</html>
