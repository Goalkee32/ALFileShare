<?php

require_once dirname(__DIR__) . "/.config/db-config.php";
require_once dirname(__DIR__) . "/model/file-model.php";

class FileController {
    private $fileModel;
    private $userId;
    private $uploadDir;

    public function __construct() {
        $Server = new Server(); // Skapar instans av Server
        $db = $Server->getConnection(); // Gör PDO koppling
        $this->fileModel = new FileModel($db); // Skapar instans av FileModel
        
        $this->settings();
    }

    // Viktiga variabler
    public function settings() {
        $this->userId = $_SESSION["user_id"];
        $this->uploadDir = "C:\\Users\\victo\\OneDrive\\Pictures\\alfileshare" . DIRECTORY_SEPARATOR . $this->userId;
        // DEBUG: "C:\\Users\\victo\\OneDrive\\Pictures\\alfileshare" . DIRECTORY_SEPARATOR . $this->userId;
        // PRODUCTION: "/home/r1nz3n/ALFileShareStorage/" . $this->userId;
    }
    public function getUploadDir() {
        return $this->uploadDir;
    }

    // Huvudmaps function
    public function directoryCheck($uploadDir) {
        // Skapar en huvudmap om en inte finns
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
    }

    // Uppladdnings funktion
    public function uploadFile($userId, $uploadDir) {
        $this->directoryCheck($uploadDir);
                
        // Hantera den uppladdade filen
        if (isset($_FILES["file"]) && $_FILES["file"]["error"] === UPLOAD_ERR_OK) {
            $fileName = basename($_FILES["file"]["name"]);
            $filePath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

            // Flyttar filen till den designerade mappen
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {

                // Sparar filvägen i databasen
                $uuid = $this->uuid();
                $file = $this->fileModel->uploadImage($uuid, $userId, $filePath);

                // Om filen sparades : Success
                if ($file) {
                    $_SESSION["success"] = "Image successfully uploaded";
                    header("Location: ../public/file-loading.php");
                    die();

                } else { $this->dieStatement("Upload was unsuccessfull"); } // Filen sparades inte, eller annat error
            } else { $this->dieStatement("An error occurred"); }
        } else { $this->dieStatement("An error occurred"); }
    }

    // Ladda filerna för att visa
    public function loadFileList($subDir = "") {
        $targetDir = $this->uploadDir;
    
        if (!empty($subDir)) {
            // Bifoga underkatalogen till baskatalogen
            $targetDir .= DIRECTORY_SEPARATOR . $subDir;
        } else {
            // Lägg till ?dir=. när ingen underkatalog finns
            $subDir = ".";
        }
    
        // Validera och sanera målkatalogen
        $currentDir = $this->sanitizeAndValidatePath($targetDir);
        $this->directoryCheck($currentDir);
    
        // Ta bort avskiljare för att visa konsistens
        $cleanSubDir = rtrim($subDir, DIRECTORY_SEPARATOR);
    
        // Läs och returnera katalogens innehåll
        $contents = scandir($currentDir);
        $filteredContents = array_filter($contents, function ($item) {
            return $item !== "." && $item !== "..";
        });
    
        return [
            "contents" => $filteredContents,
            "currentDir" => $cleanSubDir,
        ];
    }

    // Sorterar listan
    public function getSortedFileList($subDir = "") {
        $fileData = $this->loadFileList($subDir);
    
        $directories = [];
        $files = [];
    
        foreach ($fileData["contents"] as $item) {
            $itemPath = $fileData["currentDir"] === ""
                ? $item
                : $fileData["currentDir"] . DIRECTORY_SEPARATOR . $item;
    
            $isDir = is_dir(realpath($this->getUploadDir() . DIRECTORY_SEPARATOR . $itemPath));
    
            if ($isDir) {
                $directories[] = $item;
            } else {
                $files[] = $item;
            }
        }
    
        // Sorterar båda arrayer i alfabetisk ordning
        sort($directories, SORT_NATURAL | SORT_FLAG_CASE);
        sort($files, SORT_NATURAL | SORT_FLAG_CASE);
    
        return [
            "directories" => $directories,
            "files" => $files,
            "currentDir" => $fileData["currentDir"],
        ];
    }








    

    // Dödar och skickar error meddelande
    public function dieStatement($tlp) {
        $_SESSION["error"] = $tlp;
        header("Location: ../public/file-loading.php");
        die();
    }

    // Skapar UUID's
    public function uuid($data = null) {
        // Genererar 16 bytes av random data, eller använder datan passerad in i funktionen
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);
    
        // Sätt verision till 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Sätt bits 6-7 till 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    
        // Skickar ut 36 karaktärer UUID
        return vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($data), 4));
    }
    
    // Validerar sök vägen
    public function sanitizeAndValidatePath($path) {
        $realPath = realpath($path);
    
        // Kontrollera om sökvägen finns och finns i användarens katalog
        if ($realPath === false || strpos($realPath, realpath($this->uploadDir)) !== 0) {
            $this->dieStatement("Access denied.");
        }
        return $realPath;
    }
}