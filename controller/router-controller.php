<?php
require "../controller/auth-controller.php";

// Allowed
$validClasses = ["AuthController"];
$validMethods = ["login", "registration"];

// Get class, method parameters
$class = $_GET["class"] ?? "";
$method = $_GET["method"] ?? "";

// If class and method exist
if (class_exists($class) && method_exists($class, $method)) {
    $controller = new $class(); // Instantiate the class
    $controller->$method(); // Call the method
} else {
    echo "Error: Class or method not found!";
}