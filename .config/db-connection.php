<?php
// Serv initals
$dsn = "mysql:dbname=alfileshare;host=127.0.0.1";
$username = "root";
$password = "NhEtzNQ{48L3E0M"; // CHANGE THIS

function dbConnection($dsn, $username, $password) {
    $ret = "";
    try {
        $conn = new PDO($dsn, $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $ret = "Connected successfully";
    } catch(PDOException $e) {
        $ret = "Connection failed: " . $e->getMessage();
    }
    return $ret;
}