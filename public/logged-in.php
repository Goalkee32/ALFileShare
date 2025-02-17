<?php 
$_GET["class"] = "AuthController";
$_GET["method"] = "isLoggedIn";

require dirname(__DIR__) . "/controller/router-controller.php";
?>
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
        <a class="w3-button w3-bar-item" href="../include/logout.php">Logga ut</a>
        <a class="w3-button w3-bar-item" href="../public/file-loading.php">Ladda upp</a>
        
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
                echo '<h3>Filer i mappen: ' . htmlspecialchars($_GET['folder']) . '</h3>';
                
                $files = glob($selectedFolder . '/*.{jpg,jpeg,png,gif,mp4,mp3}', GLOB_BRACE);

                if ($files) {
                    echo '<div class="w3-row-padding">';
                    foreach ($files as $file) {
                        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                        echo '<div class="w3-col s4">';
                        
                        if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                            echo '<img src="' . $file . '" alt="' . basename($file) . '" style="width:100%" class="w3-hover-opacity">';
                        } elseif ($fileExtension === 'mp4') {
                            echo '<video controls style="width:100%">
                                    <source src="' . $file . '" type="video/mp4">
                                  </video>';
                        } elseif ($fileExtension === 'mp3') {
                            echo '<audio controls style="width:100%">
                                    <source src="' . $file . '" type="audio/mpeg">
                                  </audio>';
                        }
                        
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<p>Inga filer hittades i denna mapp.</p>';
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
