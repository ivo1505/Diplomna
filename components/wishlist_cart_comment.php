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

           // Perform data sanitization if needed
           $pid = filter_var($pid, FILTER_SANITIZE_STRING);
           $comment = filter_var($comment, FILTER_SANITIZE_STRING);

           // Check for an empty comment
           if (empty($comment)) {
               $message[] = 'Моля въведете валиден коментар!';
           } else {
            // Prepare the SQL query
               $insert_comment_query = $conn->prepare("INSERT INTO comments (product_id, user_id, comment) VALUES (?, ?, ?)");

               // Execute the request with the passed parameters
               $insert_comment_query->execute([$pid, $user_id, $comment]);

              // You can add logic to check the success of the request and return a message
               $message[] = 'Коментарът беше успешно добавен!';
           }
       } else {
         // If the required data is not provided, return an error message
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