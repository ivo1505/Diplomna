<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);
   $brand = $_POST['brand'];
   $brand = filter_var($brand, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $size = $_POST['size'];
   $size = filter_var($size, FILTER_SANITIZE_STRING);
   $collection = $_POST['collection'];
   $collection = filter_var($collection, FILTER_SANITIZE_STRING);
   $color = $_POST['color'];
   $color = filter_var($color, FILTER_SANITIZE_STRING);
   $gender = $_POST['gender'];
   $gender = filter_var($gender, FILTER_SANITIZE_STRING);

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/'.$image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/'.$image_03;


   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Това име вече съществува!';
   }else{

      $insert_products = $conn->prepare("INSERT INTO `products`(name, details, price, image_01, image_02, image_03, brand, category, collection, color, size, gender) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
      $insert_products->execute([$name, $details, $price, $image_01, $image_02, $image_03, $brand, $category, $collection, $color, $size, $gender]);

      if($insert_products){
         if($image_size_01 > 2000000 OR $image_size_02 > 2000000 OR $image_size_03 > 2000000){
            $message[] = 'Размера на снимката е твърде голям!';
         }else{
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'Новият продукт е добавен!';
         }

      }

   }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_03']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:products.php');
}


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
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-products">

   <h1 class="heading">Добави продукт</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <b><span>Име (задължително)</span></b>
            <input type="text" class="box" required maxlength="100" placeholder="Въведи име" name="name">
         </div>
         <div class="inputBox">
            <b><span>Цена (задължително)</span></b>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="Въведи цена" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>
         <div class="inputBox">
            <b><span>Марка (задължително)</span></b>
            <input type="text" class="box" required maxlength="100" placeholder="Въведи марка" name="brand">
         </div>
         <div class="inputBox">
            <b><span>Категория (задължително)</span></b>
            <input type="text" class="box" required maxlength="100" placeholder="Въведи категория" name="category">
         </div>
         <div class="inputBox">
            <b><span>Размери (задължително)</span></b>
            <input type="text" class="box" required maxlength="100" placeholder="Въведи наличните размери" name="size">
         </div>
         <div class="inputBox">
            <b><span>Колекция (задължително)</span></b>
            <input type="text" class="box" required maxlength="100" placeholder="Въведи име на колекция" name="collection">
         </div>
         <div class="inputBox">
            <b><span>Цветове (задължително)</span></b>
            <input type="text" class="box" required maxlength="100" placeholder="Въведи цвят" name="color">
         </div>
         </div>
         <div class="inputBox">
            <b><span>Пол (задължително)</span></b>
            <input type="text" class="box" required maxlength="100" placeholder="Въведи пол" name="gender">
         </div>
        <div class="inputBox">
            <b><span>снимка 01 (задължително)</span></b>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
        <div class="inputBox">
            <b><span>снимка 02 (задължително)</span></b>
            <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
        <div class="inputBox">
            <b><span>снимка 03 (задължително)</span></b>
            <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
         <div class="inputBox">
            <b><span>Детайли (задължително)</span></b>
            <textarea name="details" placeholder="Въведи детайли" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      
      <input type="submit" value="Добави продукт" class="btn" name="add_product">
     

   </form>

</section>
