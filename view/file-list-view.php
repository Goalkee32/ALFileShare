<?php

$_GET["class"] = "FileController";
$_GET["method"] = "loadFileList";

require dirname(__DIR__) . "../controller/router-controller.php";

$fileController = new FileController();
$subDir = isset($_GET["dir"]) ? $_GET["dir"] : "";

$sortedList = $fileController->getSortedFileList($_GET["dir"] ?? "");
$currentDir = $sortedList["currentDir"];
?>

<h3>Current Directory: <?= htmlspecialchars(trim(preg_replace("/\.+/", "", str_replace(["%5C", DIRECTORY_SEPARATOR . ".", " "], DIRECTORY_SEPARATOR, $currentDir)))) ?></h3>

<ul>
    <?php
    // Tillbaka navigation
    if ($sortedList["currentDir"] !== "."): ?>
        <li><a href="?dir=<?= urlencode(dirname($sortedList["currentDir"])) ?>">.. Gå tillbaka</a></li>
    <?php endif; ?>

    <!-- Renderar mappar -->
    <?php foreach ($sortedList["directories"] as $directory): ?>
        <li>
            <a href="?dir=<?= urlencode($sortedList["currentDir"] . DIRECTORY_SEPARATOR . $directory) ?>">
                <?= htmlspecialchars($directory) ?> (Folder)
            </a>
        </li>
    <?php endforeach; ?>

    <!-- Renderar filer -->
    <?php foreach ($sortedList["files"] as $file): ?>
        <li>
            <?= htmlspecialchars($file) ?> (File)
        </li>
    <?php endforeach; ?>
</ul>