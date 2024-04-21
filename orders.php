<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders">

   <h1 class="heading">направени поръчки</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">моля, влезте в акаунта си, за да видите вашите поръчки</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
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
      <b><p>Статус : </b><span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
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