<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update_payment'])){
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
   $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_payment->execute([$payment_status, $order_id]);
   $message[] = 'Успешно обновяване!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>placed orders</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="orders">

<h1 class="heading">направени поръчки</h1>

<div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <b><p> Изпратена на : <span></b><?= $fetch_orders['UpdationDate']; ?></span> </p>
      <b><p> Име : <span></b><?= $fetch_orders['name']; ?></span> </p>
      <b><p> Тел. номер : <span></b><?= $fetch_orders['number']; ?></span> </p>
      <b><p> Адрес : <span></b><?= $fetch_orders['address']; ?></span> </p>
      <b><p> Продукти : <span></b><?= $fetch_orders['total_products'] ?></span> </p>
      <b><p> Крайна цена : <span></b><?= $fetch_orders['total_price']; ?>$</span> </p>
      <b><p> Метод на разплащане : <span></b><?= $fetch_orders['method']; ?></span> </p>
      <form action="" method="post">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="payment_status" class="select">
    <option value="Изчакваща" <?= ($fetch_orders['payment_status'] == "Изчакваща") ? "selected" : "" ?>>Изчакваща</option>
    <option value="В куриер" <?= ($fetch_orders['payment_status'] == "В куриер") ? "selected" : "" ?>>В куриер</option>
    <option value="Завършена" <?= ($fetch_orders['payment_status'] == "Завършена") ? "selected" : "" ?>>Завършена</option>
</select>
        <div class="flex-btn">
         <input type="submit" value="Обнови" class="option-btn" name="update_payment">
         <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Изтрий поръчка?');">Изтрий</a>
        </div>
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Все още няма поръчки!</p>';
      }
   ?>

</div>

</section>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>