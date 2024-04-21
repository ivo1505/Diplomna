<?php
include 'components/connect.php';

// Проверка за съществуване на POST заявката
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверка за съществуване на общата сума
    if(isset($_POST['total_amount']) && is_numeric($_POST['total_amount'])) {
        $total_amount = $_POST['total_amount'];

        // Съхранение на информацията за поръчката
        session_start();
        $user_id = $_SESSION['user_id'];
        $name = $_POST['name'];
        $number = $_POST['number'];
        $email = $_POST['email'];
        $method = $_POST['method'];
        $address = 'flat no. '. $_POST['flat'].', '. $_POST['street'].', '. $_POST['city'].', '. $_POST['country'].' - '. $_POST['pin_code'];
        date_default_timezone_set('Europe/Sofia'); // Задаване на времевата зона според вашето местоположение
        $placed_on = date('Y-m-d H:i:s'); // Получаване на текущата дата и час
        $total_products = $_POST['total_products'];
        $total_price = $_POST['total_price'];

        // Запазване на информацията за поръчката в базата данни
        $stmt = $conn->prepare("INSERT INTO orders (user_id, name, number, email, method, address, UpdationDate, total_products, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $number, $email, $method, $address, $placed_on, $total_products, $total_price]);

        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$user_id]);

        // Редирект към страницата на PayPal с необходимите параметри
        $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // URL на страницата на PayPal за плащане
        $business_email = 'ivakabg05@gmail.com'; // Тук посочете вашия PayPal имейл

        // Генериране на данни за плащане към PayPal
        $data = array(
            'cmd' => '_xclick',
            'business' => $business_email,
            'amount' => $total_amount,
            'currency_code' => 'USD', // Валутен код
            // Добавете още параметри по необходимост
        );

        // Подготовка на данните за пренасочване
        $query_string = http_build_query($data);
        $paypal_redirect_url = $paypal_url . '?' . $query_string;

        // Пренасочване към страницата на PayPal
        header("Location: $paypal_redirect_url");
        exit();
    } else {
        // Ако общата сума липсва или не е числено значение, изведете грешка
        echo "Грешка: Невалидна обща сума за плащане.";
    }
} else {
    // Проверка за IPN заявката от PayPal
    // Проверка за валидност на отговора от PayPal
    if (isset($_POST['txn_id'])) {
        // Вашият код за обработка на IPN заявката тук
    }
}
?>