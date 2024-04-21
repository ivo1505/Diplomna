<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<?php include '../components/admin_header.php'; ?>

<section class="search-form">
<h1 class="heading">Търсене на продукти</h1>
   <form action="" method="post">
      <input type="text" name="search_box" placeholder="търси тук..." maxlength="100" class="box_heading" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>
</section>

<section class="show-products">

   <h1 class="heading">Налични продукти</h1>

   <div class="box-container">

   <?php
   // Check if the search button is pressed
   if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
      $search_box = $_POST['search_box'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE '%{$search_box}%'"); 
      $select_products->execute();
      
      // Check if there are search results
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
            $product_id = $fetch_products['id'];
            $count_comments = $conn->prepare("SELECT COUNT(*) AS total_comments FROM `comments` WHERE product_id = :product_id");
            $count_comments->bindParam(':product_id', $product_id);
            $count_comments->execute();
            $comments_result = $count_comments->fetch(PDO::FETCH_ASSOC);
            $total_comments = $comments_result['total_comments'];
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="price"><span><?= $fetch_products['price']; ?></span>$</div>
      <div class="details"><span><?= $fetch_products['details']; ?></span></div>
      <div class="comment-count">Брой коментари: <?= $total_comments; ?></div>
      <div class="flex-btn">
         <a href="update_product.php?update=<?= $fetch_products['id']; ?>"button class="edit-button">
            <svg class="edit-svgIcon" viewBox="0 0 512 512">
               <path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
            </svg>
         </a>
         <a href="products.php?delete=<?= $fetch_products['id']; ?>" onclick="return confirm('Изтриване на продукта?');"button class="button-delete">
            <svg viewBox="0 0 448 512" class="svgIcon"><path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path></svg>
         </a>
      </div>
   </div>
   <?php
         }
      } else {
         // Display message when there are no search results
         echo '<p class="empty">Няма резултати за търсенето!</p>';
      }
   } else {
      // If the search button is not pressed, display all products
      $select_all_products = $conn->prepare("SELECT * FROM `products`");
      $select_all_products->execute();
      
      // Check if there are products to display
      if($select_all_products->rowCount() > 0){
         while($fetch_products = $select_all_products->fetch(PDO::FETCH_ASSOC)){
            $product_id = $fetch_products['id'];
            $count_comments = $conn->prepare("SELECT COUNT(*) AS total_comments FROM `comments` WHERE product_id = :product_id");
            $count_comments->bindParam(':product_id', $product_id);
            $count_comments->execute();
            $comments_result = $count_comments->fetch(PDO::FETCH_ASSOC);
            $total_comments = $comments_result['total_comments']; 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="price"><span><?= $fetch_products['price']; ?></span>$</div>
      <div class="details"><span><?= $fetch_products['details']; ?></span></div>
      <div class="comment-count">Брой коментари: <?= $total_comments; ?></div>
      <div class="flex-btn">
         <a href="update_product.php?update=<?= $fetch_products['id']; ?>"button class="edit-button">
            <svg class="edit-svgIcon" viewBox="0 0 512 512">
               <path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
            </svg>
         </a>
         <a href="products.php?delete=<?= $fetch_products['id']; ?>" onclick="return confirm('Изтриване на продукта?');"button class="button-delete">
            <svg viewBox="0 0 448 512" class="svgIcon"><path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path></svg>
         </a>
      </div>
   </div>
   <?php
         }
      } else {
         // Display message when there are no products
         echo '<p class="empty">Все още няма добавени продукти!</p>';
      }
   }
   ?>
   
   </div>

</section>


<script src="../js/admin_script.js"></script>
   
</body>
</html>
<style>
   .comment-count {
    font-size: 19px;
    color: #333;
    margin-top: 5px;
}

.comment-icon {
    margin-right: 5px;
}

.count-label {
    font-weight: bold;
}

.count {
    color: #007bff; /* Change color as desired */
}
  .box_heading{
   padding-right: 220px;
  }

  .search-form {
            text-align: center;
            
        }

        form {
            display: inline-block;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding-top: 10px;
            padding-bottom: 10px;
            padding-left: 25px;
            padding-right: 25px;
        }

       
  .edit-button {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: rgb(20, 20, 20);
  border: none;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
  cursor: pointer;
  transition-duration: 0.3s;
  overflow: hidden;
  position: relative;
  text-decoration: none !important;
}

.edit-svgIcon {
  width: 17px;
  transition-duration: 0.3s;
}

.edit-svgIcon path {
  fill: white;
}

.edit-button:hover {
  width: 120px;
  border-radius: 50px;
  transition-duration: 0.3s;
  background-color: rgb(255, 69, 69);
  align-items: center;
}

.edit-button:hover .edit-svgIcon {
  width: 20px;
  transition-duration: 0.3s;
  transform: translateY(60%);
  -webkit-transform: rotate(360deg);
  -moz-transform: rotate(360deg);
  -o-transform: rotate(360deg);
  -ms-transform: rotate(360deg);
  transform: rotate(360deg);
}

.edit-button::before {
  display: none;
  content: "Редакция";
  color: white;
  transition-duration: 0.3s;
  font-size: 2px;
}

.edit-button:hover::before {
  display: block;
  padding-right: 10px;
  font-size: 13px;
  opacity: 1;
  transform: translateY(0px);
  transition-duration: 0.3s;
}
.button-delete {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background-color: rgb(20, 20, 20);
  border: none;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
  cursor: pointer;
  transition-duration: .3s;
  overflow: hidden;
  position: relative;
}

.svgIcon {
  width: 12px;
  transition-duration: .3s;
}

.svgIcon path {
  fill: white;
}

.button-delete:hover {
  width: 140px;
  border-radius: 50px;
  transition-duration: .3s;
  background-color: rgb(255, 69, 69);
  align-items: center;
}

.button-delete:hover .svgIcon {
  width: 50px;
  transition-duration: .3s;
  transform: translateY(60%);
}

.button-delete::before {
  position: absolute;
  top: -20px;
  content: "Изтриване";
  color: white;
  transition-duration: .3s;
  font-size: 2px;
}

.button-delete:hover::before {
  font-size: 13px;
  opacity: 1;
  transform: translateY(30px);
  transition-duration: .3s;
}


</style>