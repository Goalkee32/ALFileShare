<?php
require_once "credentials.php";

$servername = "MySQL80";
$username = "root";
$password = "NhEtzNQ{48L3E0M";

try {
    $conn = new PDO("mysql:host=$servername;dbname=database", $username, $password);
    echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}