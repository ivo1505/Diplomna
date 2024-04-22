<?php
  
    
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../ccs/style.css">

    <title>User header</title>
</head>
<body>
    
<header class="header">

<section class="flex">

<a href="index.php" class="logo-desing">FootX</a>
<ul class="dropdown">
     <li><a href="#">Категории</a>
         <ul>
             <li><a href="#">Мъже</a> 
                 <ul>
                     <li><a href="Filter.php?brand=Air Jordan&collection=мъже">Nike Air Jordan</a></li>
                     <li><a href="Filter.php?brand=Air Max&collection=мъже">Nike Air Max</a></li>
                     <li><a href="Filter.php?brand=Adidas&collection=мъже">Adidas</a>
                     <li><a href="Filter.php?brand=Puma&collection=мъже">Puma</a>
                     <li><a href="Filter.php?brand=Vans&collection=мъже">Vans</a>
                         <!-- <ul>
                             <li><a href="">Submenu - 1</a></li>
                             <li><a href="">Submenu - 2</a></li>
                             <li><a href="">Submenu - 3</a></li>
                         </ul> -->
                     </li>
                 </ul>
             </li>
             <li><a href="#">Жени</a>
                 <ul>
                     <li><a href="Filter.php?brand=Air Jordan&collection=жени">Nike Air Jordan</a></li>
                     <li><a href="Filter.php?brand=Air Max&collection=жени">Nike Air Max</a></li>
                     <li><a href="Filter.php?brand=Adidas&collection=жени">Adidas</a></li>
                     <li><a href="Filter.php?brand=Puma&collection=жени">Puma</a></li>
                     <li><a href="Filter.php?brand=Vans&collection=жени">Vans</a></li>
                 </ul>
             </li>
             <li><a href="#">Деца</a>
                 <ul>
                     <li><a href="Filter.php?brand=Air Jordan&collection=деца">Nike Air Jordan</a></li>
                     <li><a href="Filter.php?brand=Air Max&collection=деца">Nike Air Max</a></li>
                     <li><a href="Filter.php?brand=Adidas&collection=деца">Adidas</a></li>
                     <li><a href="Filter.php?brand=Puma&collection=деца">Puma</a></li>
                     <li>  <a href="Filter.php?brand=Vans&collection=деца">Vans</a></li>
                 </ul>
             </li>
         </ul>
     </li>
 </ul>

</div>
<div class="wrap">
    
          <button class="button"><a href="index.php">Начало</a></button>
          <button class="button"><a href="about.php">За нас</a></button>
          <button class="button"><a href="orders.php">Поръчки</a></button>
          <button class="button"><a href="shop.php">Пазарувай</a></button>
          <button class="button"><a href="contact.php">Контакти</a></button>
    
       <div class="icons" style="width:300px;">
          <?php
             $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
             $count_wishlist_items->execute([$user_id]);
             $total_wishlist_counts = $count_wishlist_items->rowCount();
    
             $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
             $count_cart_items->execute([$user_id]);
             $total_cart_counts = $count_cart_items->rowCount();
          ?>
          <div id="menu-btn" class="fas fa-bars"></div>
          <a href="search_page.php"><i class="fas fa-search"></i></a>
          <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $total_wishlist_counts; ?>)</span></a>
          <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_counts; ?>)</span></a>
          <div id="user-btn" class="fas fa-user"></div>
       </div>
</div>

   <div class="profile">
      <?php          
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         if($select_profile->rowCount() > 0){
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <class> <b><p>Потребителско име:</b> <?= $fetch_profile['name']; ?></p>
      <a href="update_user.php" class="btn">актуализиране на профила</a>
      <?php
 if ($user_id == '') {
     echo '<div class="flex-btn">';
     echo '<a href="user_register.php" class="option-btn">Регистрация</a>';
     echo '<a href="user_login.php" class="option-btn">Вписване</a>';
     echo '</div>';
 } else {
 }
?>
      <a href="components/user_logout.php" class="delete-btn" onclick="return confirm('Сигурно ли сте, че искате да излезнете от профила си?');">Отписване</a> 
      <?php
         }else{
      ?>
      <p>Моля първо влезте или се регистрирайте!</p>
      <div class="flex-btn">
         <a href="user_register.php" class="option-btn">Регистрация</a>
         <a href="user_login.php" class="option-btn">Вписвяане</a>
      </div>
      <?php
         }
      ?>      
      
      
   </div>

</section>

</header>
</body>
</html>
