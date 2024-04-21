<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
    exit(); // Не забравяйте да добавите exit() след пренасочването, за да спрете изпълнението на кода долу
}

// Проверка за съществуване на POST заявката
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверка за съществуване на общата сума
    if(isset($_POST['total_amount']) && is_numeric($_POST['total_amount'])) {
        $total_amount = $_POST['total_amount']; // Променено от $POST на $_POST

        // Редирект към страницата на PayPal с необходимите параметри
        $paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // URL на страницата на PayPal за плащане
        $business_email = 'вашпейпъл_имейл@example.com'; // Тук посочете вашия PayPal имейл

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
    // Ако заявката не е POST, изведете грешка
    echo "Грешка: Невалидна заявка.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout_view_list</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   

<section class="orders">

   <h1 class="heading">Списък на поръчката</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">моля, влезте в акаунта си, за да видите вашите поръчки</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY UpdationDate DESC LIMIT 1");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <b><p>Изпратена на : <span></b><?= $fetch_orders['UpdationDate']; ?></span></p>
      <b><p>Име : <span></b><?= $fetch_orders['name']; ?></span></p>
      <b><p>Имейл : <span></b><?= $fetch_orders['email']; ?></span></p>
      <b><p>Тел. номер : <span></b><?= $fetch_orders['number']; ?></span></p>
      <b><p>Адрес : <span></b><?= $fetch_orders['address']; ?></span></p>
      <b><p>Метод на плащане : <span></b><?= $fetch_orders['method']; ?></span></p>
      <b><p>Поръчка : <span></b><?= $fetch_orders['total_products']; ?></span></p>
      <b><p>Крайна цена : <span></b><?= $fetch_orders['total_price']; ?>$</span></p>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">Все още няма поръчки!</p>';
      }
      }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
