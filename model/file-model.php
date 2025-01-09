<?php

class FileModel {
    private $db;

    // Konstruktor accepterar PDO koppling
    public function __construct($db) {
        $this->db = $db;
    }

    // Skickar fil och data till databasen
    public function uploadImage($uuid, $userId, $filePath) {
        $sql = "INSERT INTO content (uuid, user_id, file_path) VALUES (:uuid, :user_id, :file_path)";
        $stmt = $this->db->prepare($sql);
        $file = $stmt->execute(["uuid" => $uuid, "user_id" => $userId, "file_path" => $filePath]);
        return $file;
    }

}