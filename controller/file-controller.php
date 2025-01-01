<?php

require_once dirname(__DIR__) . "../.config/db-config.php";
require_once dirname(__DIR__) . "../model/file-model.php";

class FileController {
    private $fileModel;

    public function __construct() {
        $Server = new Server(); // Skapar instans av Server
        $db = $Server->getConnection(); // Gör PDO koppling
        $this->fileModel = new FileModel($db); // Skapar instans av FileModel
    }

    // Huvudmaps function
    public function directoryCheck($uploadDir) {
        // Skapar en huvudmap om en inte finns
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
    }

    // Uppladdnings funktion
    public function uploadFile() {
        $uploadDir = 'C:\Users\victo\OneDrive\Pictures\alfileshare';
        $this->directoryCheck($uploadDir);
                
        // Hantera den uppladdade filen
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileName = basename($_FILES['file']['name']);
            $filePath = $uploadDir . '\\' . $fileName;

            // Flyttar filen till den designerade mappen
            if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {

                // Sparar filvägen i databasen
                $userId = $_SESSION['user_id'];
                $file = $this->fileModel->uploadImage($userId, $filePath);

                // Om filen sparades : Success
                if ($file) {
                    $_SESSION["success"] = "Image successfully uploaded";
                    header("Location: ../public/file-loading.php");
                    die();

                } else { $this->dieStatement("Upload was unsuccessfull"); } // Filen sparades inte, eller annat error
            } else { $this->dieStatement("An error occurred"); }
        } else { $this->dieStatement("An error occurred"); }
    }


    
    public function dieStatement($tlp) {
        $_SESSION["error"] = $tlp;
        header("Location: ../public/file-loading.php");
        die();
    }
}