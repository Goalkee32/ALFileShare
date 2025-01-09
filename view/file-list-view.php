<?php

$_GET["class"] = "FileController";
$_GET["method"] = "loadFileList";

require dirname(__DIR__) . "../controller/router-controller.php";

$fileController = new FileController();
$fileList = $fileController->loadFileList();
?>

<?php if (!empty($fileList)): ?>
    <ul>
        <?php foreach ($fileList as $item): ?>
            <li><?= htmlspecialchars($item) ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Inga filer eller mappar hittades.</p>
<?php endif; ?>