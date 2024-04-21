<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart_comment.php';

if(isset($_POST['submit'])) {
   // Check if the comment edit form has been submitted.   
   $comment_id = $_POST['comment_id'];
   $edited_comment = $_POST['edited_comment'];
   $edited_comment = filter_var($edited_comment, FILTER_SANITIZE_STRING);
   
   // Update the comment in the database.
   $update_comment = $conn->prepare("UPDATE comments SET comment = ? WHERE id = ?");
   $update_comment->execute([$edited_comment, $comment_id]);
   
   // Check for successful comment editing and message sending.
   if ($update_comment) {
       $message[] = 'Коментарът е успешно редактиран.';
   } else {
       $message[] = 'Грешка при редактиране на коментара.';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>quick view</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view">

   <h1 class="heading">Преглед</h1>

   <?php
     $pid = $_GET['pid'];
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?"); 
     $select_products->execute([$pid]);
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <div class="row">
         <div class="image-container">
            <div class="main-image">
               <img id="mainImage" src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
            </div>
            <div class="sub-image">
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" alt="">
            </div>
         </div>


         <div class="content">
            <div class="name"><?= $fetch_product['name']; ?></div>
            <div class="flex">
               <div class="price"><span><b>Цена: </b></span><?= $fetch_product['price']; ?><span>$</span></div>
               <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
            </div>
            <label for="text"><b>Колекция:</b></label>
            <div class="details"><?= $fetch_product['collection']; ?></div>
            <label for="text"><b>Категория:</b></label>
            <div class="details"><?= $fetch_product['category']; ?></div>
            <label for="text"><b>Цветове:</b></label>
            <div class="details"><?= $fetch_product['color']; ?></div>
            <div class="name">
            <label for="text"><b>Детайли за продукта:</b></label>
            <div class="details"><?= $fetch_product['details']; ?></div>
            <div class="name">

  <label for="text"><b>Налични размери:</b></label>
  <select id="size" name="size"><br>
    <?php
    $available_sizes = explode(',', $fetch_product['size']);
    foreach ($available_sizes as $size) {
      $selected = ($size === $selected_size) ? 'selected' : ''; // Check if the current size is selected.
      echo "<option value=\"$size\" $selected>$size</option>"; // Add the dimensions to the dropdown.
    }
     
    ?>
   
  </select>
</div>
            <div class="flex-btn">
               <br><input type="submit" value="Добави в количка" class="button" name="add_to_cart">
               <br><input class="button" type="submit" name="add_to_wishlist" value="Добави в любими">
            </div>

         </div>
         
      </div>

    <h3 class="heading">Въведи коментар</h3>
    <textarea name="comment" placeholder="Въведете коментар"></textarea>
    <input type="hidden" name="location_id" value="<?php echo $fetch_product['id']; ?>">
    <div class = "center">
    <input type="submit" value="Изпрати коментар" class="btn" name="add_comment">
    </div>
   </form>
   
   <?php
      }
   }else{
      echo '<p class="empty">Няма добавени продукти!</p>';
   }
   ?>

</section>

<section class="quick-view">

<h2 class="heading">Коментари</h2>
<?php
$pid = $_GET['pid'];
$select_comments = $conn->prepare("SELECT comments.*, users.name AS user_name FROM comments JOIN users ON comments.user_id = users.id WHERE comments.product_id = ?");

if ($select_comments->execute([$pid])) {
    if ($select_comments->rowCount() > 0) {
        while ($comment = $select_comments->fetch(PDO::FETCH_ASSOC)) {
         echo '<div class="comment-section">';
         echo '<p><strong>Качено от:</strong> ';
         if ($comment['user_name']) {
             echo 'Потребител: ' . $comment['user_name'];
         } else {
             echo 'Неизвестен потребител или администратор';
         }
         echo '</p>';
         echo '<p><strong>Коментар:</strong> ' . $comment['comment'] . '</p>';
         echo '<p><strong>Качен на:</strong> ' . $comment['created_at'] . '</p>';
         if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']) {
             echo '<form method="post" action="">';
             echo '<input type="hidden" name="comment_id" value="' . $comment['id'] . '">';
             echo '<textarea name="edited_comment">' . $comment['comment'] . '</textarea>';
             echo '<button type="submit" class = "button "name="submit">Запази промените</button>';
             echo '</form>';
         }
         echo '</div>';
            
        }

        // Close the cursor after exiting the loop.
        $select_comments->closeCursor();
    } else {
        echo '<p class="empty">Няма коментари за този продукт.</p>';
    }
} else {
    echo 'Грешка при изпълнението на заявката за коментарите';
}
?>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<style>
     /* Styles for textarea */    
      textarea {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 4px;
      margin-bottom: 10px;
    }

     /* Styles for the hidden input field */
    input[type="hidden"] {
      /* Your code may look different depending on your requirements */
      display: none;
    }    select {
        font-size: 16px;/* You can change 16px to whatever size you want */
    }
    h3{
       margin-left: 360px;
    }
    .center {
        margin-left: 450px;
    }
  </style>
</body>
</html>

