<?php
require "../controller/auth-controller.php";
require "../controller/file-controller.php";

// Tillåtna
$validClasses = ["AuthController", "FileController"];
$validMethods = ["login", "registration", "isLoggedIn", "logout", "uploadFile"];

// Tar class och method parametrarna
$class = $_GET["class"] ?? "";
$method = $_GET["method"] ?? "";

// Om class och method finns
if (class_exists($class) && method_exists($class, $method)) {
    $controller = new $class(); // Instantiera klassen
    $controller->$method(); // Kalla på methoden
} else {
    echo "Error: Class or method not found!";
}