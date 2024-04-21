
<?php
$server = 'localhost';
$user = 'shoedmoy';
$password = 'mV8cX)c5Nc^J';
$database = 'shop_db';

$conn = new PDO($db_name, $user_name, $user_password);

if ($conn->connect_error) {
    header('Location: ../DatabaseError.php');
}
?>