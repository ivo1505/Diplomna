<?php
include 'components/connect.php';

// Check for the existence of the POST request.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for existence of the total.
    if(isset($_POST['total_amount']) && is_numeric($_POST['total_amount'])) {
        $total_amount = $_POST['total_amount'];

        // Store the order information.
        session_start();
        $user_id = $_SESSION['user_id'];
        $name = $_POST['name'];
        $number = $_POST['number'];
        $email = $_POST['email'];
        $method = $_POST['method'];
        $address = 'flat no. '. $_POST['flat'].', '. $_POST['street'].', '. $_POST['city'].', '. $_POST['country'].' - '. $_POST['pin_code'];
        date_default_timezone_set('Europe/Sofia'); // Set the time zone according to your location.
        $placed_on = date('Y-m-d H:i:s'); // Get the current date and time.
        $total_products = $_POST['total_products'];
        $total_price = $_POST['total_price'];
        

        // Save the order information to the database.
        $stmt = $conn->prepare("INSERT INTO orders (user_id, name, number, email, method, address, UpdationDate, total_products, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $number, $email, $method, $address, $placed_on, $total_products, $total_price]);

        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$user_id]);

        // Redirect to PayPal page with required parameters
        $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // PayPal payment page URL.
        $business_email = 'ivakabg05@gmail.com'; // Enter your PayPal email here.

        // Generate payment data to PayPal.
        $data = array(
            'cmd' => '_xclick',
            'business' => $business_email,
            'amount' => $total_amount,
            'currency_code' => 'USD', // Currency code.
            // Add more parameters as needed.
        );

        // Prepare data for forwarding.
        $query_string = http_build_query($data);
        $paypal_redirect_url = $paypal_url . '?' . $query_string;

        // Redirect to PayPal page.
        header("Location: $paypal_redirect_url");
        exit();
    } else {
        // If the total is missing or not a numeric value, throw an error.
        echo "Грешка: Невалидна обща сума за плащане.";
    }
} else {
   // Check for the IPN request from PayPal.
    // Validate response from PayPal.
    if (isset($_POST['txn_id'])) {
        // Your code to handle the IPN request here.
    }
}
?>