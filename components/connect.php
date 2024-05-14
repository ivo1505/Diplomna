<?php
$server = 'localhost';
$user = 'root';
$password = '';
$database = 'shop_db';

// Define DSN
$dsn = "mysql:host=$server;dbname=$database;charset=utf8mb4";

try {
    // Create a new PDO instance
    $conn = new PDO($dsn, $user, $password);
    
    // Set PDO to throw exceptions on error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Redirect to an error page if connection fails
    header('Location: ../DatabaseError.php');
    exit(); // Terminate script execution
}
?>
