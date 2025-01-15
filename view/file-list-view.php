<?php

$_GET["class"] = "FileController";
$_GET["method"] = "loadFileList";

require dirname(__DIR__) . "../controller/router-controller.php";

$fileController = new FileController();
$subDir = isset($_GET['dir']) ? $_GET['dir'] : '';
$fileData = $fileController->loadFileList($subDir);
$fileList = $fileData['contents'];
$currentDir = $fileData['currentDir'];
?>

<h3>Current Directory: <?= htmlspecialchars(trim(preg_replace('/\.+/', '', str_replace(['%5C', DIRECTORY_SEPARATOR . '.', ' '], DIRECTORY_SEPARATOR, $currentDir)))) ?></h3>


<ul>
    <?php if ($currentDir !== "."): ?>
        <li><a href="?dir=<?= urlencode(dirname($currentDir)) ?>">.. Gå tillbaka</a></li>
    <?php endif; ?>
    <?php foreach ($fileList as $item): ?>
        <?php
        $itemPath = $currentDir === "" 
            ? $item 
            : $currentDir . DIRECTORY_SEPARATOR . $item;
        $isDir = is_dir(realpath($fileController->getUploadDir() . DIRECTORY_SEPARATOR . $itemPath));
        ?>
        <li>
            <?php if ($isDir): ?>
                <a href="?dir=<?= urlencode($itemPath) ?>"><?= htmlspecialchars($item) ?> (Folder)</a>
            <?php else: ?>
                <?= htmlspecialchars($item) ?> (File)
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>