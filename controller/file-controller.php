<?php

require_once dirname(__DIR__) . "../.config/db-config.php";
require_once dirname(__DIR__) . "../model/file-model.php";

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
        $this->userId = $_SESSION['user_id'];
        $this->uploadDir = 'C:\Users\victo\OneDrive\Pictures\alfileshare' . '\\' . $this->userId;
    }

    // Huvudmaps function
    public function directoryCheck($uploadDir) {
        $this->settings();
        // Skapar en huvudmap om en inte finns
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
    }

    // Uppladdnings funktion
    public function uploadFile($userId, $uploadDir) {
        $this->directoryCheck($uploadDir);
                
        // Hantera den uppladdade filen
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileName = basename($_FILES['file']['name']);
            $filePath = $uploadDir . '\\' . $fileName;

            // Flyttar filen till den designerade mappen
            if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {

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
    public function loadFileList() {
        // Sanitiera fil länken för att undvika fil länks intrång
        $currentDir = realpath($this->uploadDir);
        $this->directoryCheck($currentDir);

        // Håller koll på att användaren håller sig inom sin fil länk
        if (strpos($currentDir, $this->uploadDir) !== 0) {
            die("Access denied.");
        }

        // Läser länkens innehåll
        $contents = scandir($currentDir);

        // Filter out `.` and `..`
        $filteredContents = array_filter($contents, function ($item) {
            return $item !== "." && $item !== "..";
        });
        return $filteredContents;
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
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}