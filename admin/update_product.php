<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);
   $size = $_POST['size'];
   $size = filter_var($size, FILTER_SANITIZE_STRING);

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, price = ?, details = ?, size = ? WHERE id = ?");
   $update_product->execute([$name, $price, $details, $size, $pid]);


   $message[] = 'Продуктът е обновен успешно!';

   $old_image_01 = $_POST['old_image_01'];
   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   if(!empty($image_01)){
      if($image_size_01 > 2000000){
         $message[] = 'Размера на снимката е твърде голям!';
      }else{
         $update_image_01 = $conn->prepare("UPDATE `products` SET image_01 = ? WHERE id = ?");
         $update_image_01->execute([$image_01, $pid]);
         move_uploaded_file($image_tmp_name_01, $image_folder_01);
         unlink('../uploaded_img/'.$old_image_01);
         $message[] = 'image 01 е обновена успешно!';
      }
   }

   $old_image_02 = $_POST['old_image_02'];
   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/'.$image_02;

   if(!empty($image_02)){
      if($image_size_02 > 2000000){
         $message[] = 'image size is too large!';
      }else{
         $update_image_02 = $conn->prepare("UPDATE `products` SET image_02 = ? WHERE id = ?");
         $update_image_02->execute([$image_02, $pid]);
         move_uploaded_file($image_tmp_name_02, $image_folder_02);
         unlink('../uploaded_img/'.$old_image_02);
         $message[] = 'image 02 е обновена успешно!';
      }
   }

   $old_image_03 = $_POST['old_image_03'];
   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/'.$image_03;

   if(!empty($image_03)){
      if($image_size_03 > 2000000){
         $message[] = 'Рамера на снимката е твърде голям!';
      }else{
         $update_image_03 = $conn->prepare("UPDATE `products` SET image_03 = ? WHERE id = ?");
         $update_image_03->execute([$image_03, $pid]);
         move_uploaded_file($image_tmp_name_03, $image_folder_03);
         unlink('../uploaded_img/'.$old_image_03);
         $message[] = 'image 03 е обновена успешно!';
      }
   }
   if (!$update_product->execute([$name, $price, $details, $size, $pid])) {
      $errorInfo = $update_product->errorInfo();
      $message[] = 'Грешка при обновяване на продукта: ' . $errorInfo[2];
  } 
}

if (isset($_POST['delete_comment'])) {
   // Delete the comment from the database
   $comment_id = $_POST['comment_id'];
   $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
   $delete_comment->execute([$comment_id]);

   // Redirect to previous page after delete
   header("Location: {$_SERVER['HTTP_REFERER']}");
   exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update product</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="update-product">

   <h1 class="heading">Обнови продукт</h1>

   <?php
      $update_id = $_GET['update'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$update_id]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image_01" value="<?= $fetch_products['image_01']; ?>">
      <input type="hidden" name="old_image_02" value="<?= $fetch_products['image_02']; ?>">
      <input type="hidden" name="old_image_03" value="<?= $fetch_products['image_03']; ?>">
      <div class="image-container">
         <div class="main-image">
            <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
         </div>
         <div class="sub-image">
            <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
            <img src="../uploaded_img/<?= $fetch_products['image_02']; ?>" alt="">
            <img src="../uploaded_img/<?= $fetch_products['image_03']; ?>" alt="">
         </div>
      </div>
      <span>Обнови име:</span>
      <input type="text" name="name" required class="box" maxlength="100" placeholder="enter product name" value="<?= $fetch_products['name']; ?>">
      <span>Обнови цена:</span>
      <input type="number" name="price" required class="box" min="0" max="9999999999" placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;" value="<?= $fetch_products['price']; ?>">
      <span>Обнови детайли:</span>
      <textarea name="details" class="box" required cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
      <span>Обнови размер:</span>
      <input type="text" name="size" required class="box" maxlength="100" value="<?= $fetch_products['size']; ?>">
      <span>Обнови снимка 01</span>
      <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      <span>Обнови снимка 02</span>
      <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      <span>Обнови снимка 03</span>
      <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      <div class="flex-btn">
         <input type="submit" name="update" class="btn" value="Обнови">
         <a href="productsfunc.php" class="option-btn">Върни се</a>
      </div>
   </form>
   
   <?php
         }
      }else{
         echo '<p class="empty">Няма намерени налични продукти!</p>';
      }
   ?>

</section>

<section class="comments-section">
   <h2 class="heading">Коментари към продукта</h2>
   <?php
   // Retrieve the comments from the database
      $product_id = $_GET['update'];
      $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE product_id = ?");
      $select_comments->execute([$product_id]);

     // Check for comments
      if ($select_comments->rowCount() > 0) {
         // Fetch the comments and render them
         while ($comment = $select_comments->fetch(PDO::FETCH_ASSOC)) {
            // Retrieve the username
            $user_id = $comment['user_id'];
            $select_user = $conn->prepare("SELECT name FROM `users` WHERE id = ?");
            $select_user->execute([$user_id]);
            $username = $select_user->fetchColumn();
   ?>
            <div class="comment-section">
               <p><strong>Потребител:</strong> <?= $username; ?> | 
               <strong>Коментар:</strong> <?= $comment['comment']; ?> | 
               <strong>Дата на създаване:</strong> <?= $comment['created_at']; ?>
               <!-- Comment delete form -->
               <form style="display:inline;" action="" method="post">
    <input type="hidden" name="comment_id" value="<?= $comment['id']; ?>">
    <button type="submit" class="btn"  onclick="return confirm('Изтриване на коментара?');"name="delete_comment">Изтрий коментара</button>
</form>
               </p>
            </div>
   <?php
         }
      } else {
         echo '<p>Няма налични коментари за този продукт.</p>';
      }
   ?>
</section>

<script src="../js/admin_script.js"></script>
   
</body>

<style>
   .comment-section {
   border: 1px solid #ccc;
   padding: 10px;
   margin: 10px 0;
   background-color: #f9f9f9;
   font-size: 16px; 
}

.comment-section p {
   margin: 5px 0;
   font-size: 18px; 
}

.comment-section strong {
   color: #333;
   font-size: 18px; 
}

.comment-section .user-info {
   font-style: italic;
   color: #777;
   font-size: 20px; 
}
</style>

</html>