<?php

if(isset($_POST['add_to_wishlist'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $size = $_POST['size'];
      $size = filter_var($size, FILTER_SANITIZE_STRING);
      
      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$name, $user_id]);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $message[] = 'Вече добавено в любими!';
      }elseif($check_cart_numbers->rowCount() > 0){
         $message[] = 'Вече добавено в количка!';
      }else{
         $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image,size) VALUES(?,?,?,?,?,?)");
         $insert_wishlist->execute([$user_id, $pid, $name, $price, $image, $size]);
         $message[] = 'Успешно добавено в любими!';
      }

   }

}
if (isset($_POST['add_comment'])) {
   if ($user_id == '') {
       header('location:user_login.php');
   } else {
       if (isset($_POST['pid'], $_POST['comment'])) {
           $pid = $_POST['pid'];
           $comment = $_POST['comment'];

           // Извършване на санитизация на данните, ако е необходимо
           $pid = filter_var($pid, FILTER_SANITIZE_STRING);
           $comment = filter_var($comment, FILTER_SANITIZE_STRING);

           // Проверка за празен коментар
           if (empty($comment)) {
               $message[] = 'Моля въведете валиден коментар!';
           } else {
               // Подготовка на SQL заявката
               $insert_comment_query = $conn->prepare("INSERT INTO comments (product_id, user_id, comment) VALUES (?, ?, ?)");

               // Изпълнение на заявката с подадените параметри
               $insert_comment_query->execute([$pid, $user_id, $comment]);

               // Можете да добавите логика за проверка на успешността на заявката и да върнете съобщение
               $message[] = 'Коментарът беше успешно добавен!';
           }
       } else {
           // Ако не са предоставени необходимите данни, върнете съобщение за грешка
           $message[] = 'Не са предоставени всички необходими данни за добавяне на коментар!';
       }
   }
}



if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);
      $size = $_POST['size'];
      $size = filter_var($size, FILTER_SANITIZE_STRING);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_cart_numbers->rowCount() > 0){
         $message[] = 'Вече добавено в количката!';
      }else{

         $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
         $check_wishlist_numbers->execute([$name, $user_id]);

         if($check_wishlist_numbers->rowCount() > 0){
            $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
            $delete_wishlist->execute([$name, $user_id]);
         }

         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image, size) VALUES(?,?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image, $size]);
         $message[] = 'Успешно добавено в количката!';
         
      }

   }

}

?>