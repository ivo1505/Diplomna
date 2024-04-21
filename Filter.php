<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart_comment.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>category</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

<?php
if (isset($_GET['brand']) && isset($_GET['collection'])){
    $brand = htmlspecialchars($_GET['brand']);
    $collection = htmlspecialchars($_GET['collection']);
    echo "<h1 class='heading'>Сортирани по марка: <span>{$brand}</span> и колекция: <span>{$collection}</span></h1>";
}else{
    
}
?>


   <div class="box-container">
   <?php
if(isset($_POST['filter_price'])) {
    // Код за обработка на формата за филтриране по цена
    $filter_price = $_POST['filter_price']; // Добавете този ред за инициализиране на стойността на $filter_price
    $select_filtered_products = $conn->prepare("SELECT * FROM `filtered_products_dropdown_menu`  WHERE `price` <= :filter_price");
    $select_filtered_products->bindParam(':filter_price', $filter_price, PDO::PARAM_INT);
    $select_filtered_products->execute();

    // Проверка за наличие на резултати
    if ($select_filtered_products->rowCount() > 0) {
        while ($fetch_filtered_product = $select_filtered_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
      <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_filtered_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_filtered_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_filtered_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_filtered_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_filtered_product['id']; ?>" class="fas fa-eye"></a>
      <img class="default-image" src="uploaded_img/<?= $fetch_filtered_product['image_01']; ?>" alt="">
      <img class="hover-image" src="uploaded_img/<?= $fetch_filtered_product['image_02']; ?>" alt="">
      <div class="name"><?= $fetch_filtered_product['name']; ?></div>
      <div class="flex">
         <div class="price">Цена: <span></span><?= $fetch_filtered_product['price']; ?><span>$</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <label for="text"><b>Налични размери:</b></label>
      <select id="size" name="size">
    <?php
    $available_sizes = explode(',', $fetch_filtered_product['size']);
    foreach ($available_sizes as $size) {
      $selected = ($size === $selected_size) ? 'selected' : ''; // Проверка дали текущия размер е избран
      echo "<option value=\"$size\" $selected>$size</option>"; // Добавяне на размерите към падащото меню
    }
     
    ?>
      <input type="submit" value="Добави в количка" class="btn" name="add_to_cart">
   </form>
            <?php
        }
    } else {
        echo '<p class="empty">Няма намерени налични продукти след филтрирането!</p>';
    }
} else {
    // Код за извличане на резултатите от таблицата products (ако не е натиснат бутона "Филтрирай")

    $brand = $_GET['brand'];
    $collection = $_GET['collection'];

    $brandPattern = "%$brand%";
    $collectionPattern = "%$collection%";

    // Изтриване на старите записи
    $delete_old_records = $conn->prepare("DELETE FROM `filtered_products_dropdown_menu`");
    $delete_old_records->execute();

    $select_products = $conn->prepare("SELECT * FROM `products` WHERE brand LIKE :brand AND collection LIKE :collection");
    $select_products->bindParam(':brand', $brandPattern, PDO::PARAM_STR);
    $select_products->bindParam(':collection', $collectionPattern, PDO::PARAM_STR);
    $select_products->execute();

    while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
        // Извършете вмъкването в новата таблица (filtered_products)
        $insert_filter = $conn->prepare("INSERT INTO `filtered_products_dropdown_menu` (product_id, name, price, image_01, image_02, size) 
                                        VALUES (:product_id, :name, :price, :image_01, :image_02, :size)");
        $insert_filter->bindParam(':product_id', $fetch_product['id'], PDO::PARAM_INT);
        $insert_filter->bindParam(':name', $fetch_product['name'], PDO::PARAM_STR);
        $insert_filter->bindParam(':price', $fetch_product['price'], PDO::PARAM_STR);
        $insert_filter->bindParam(':image_01', $fetch_product['image_01'], PDO::PARAM_STR);
        $insert_filter->bindParam(':image_02', $fetch_product['image_02'], PDO::PARAM_STR);
        $insert_filter->bindParam(':size', $fetch_product['size'], PDO::PARAM_STR);
        $insert_filter->execute();

        ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img class="default-image" src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <img class="hover-image" src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price">Цена: <span></span><?= $fetch_product['price']; ?><span>$</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <label for="text"><b>Налични размери:</b></label>
      <select id="size" name="size">
    <?php
    $available_sizes = explode(',', $fetch_product['size']);
    foreach ($available_sizes as $size) {
      $selected = ($size === $selected_size) ? 'selected' : ''; // Проверка дали текущия размер е избран
      echo "<option value=\"$size\" $selected>$size</option>"; // Добавяне на размерите към падащото меню
    }
     
    ?>
      <input type="submit" value="Добави в количка" class="btn" name="add_to_cart">
   </form>
   <?php
   }

if ($select_products->rowCount() == 0) {
    echo '<p class="empty">Няма намерени налични продукти!</p>';
}
}
?>

   </div>

   <div class="price-filter-form-container">
   <form method="post" action="">
      <label for="filter_price">Филтриране по цена:</label>
      <input type="range" min="1" max="1000" value="500" class="slider" name="filter_price" id="filter_price" step="1">
      <p>Избрана стойност: <span id="selected_value">500</span>$</p>
      <input type="submit" value="Филтрирай" name="price-filter-submit-button" onclick="toggleSection()">
   </form>
   </div>

   <script>
   
   var slider = document.getElementById("filter_price");
   var output = document.getElementById("selected_value");
   output.innerHTML = slider.value; // Display the default slider value
 
   // Update the current slider value (each time you drag the slider handle)
   slider.oninput = function() {
     output.innerHTML = this.value;
   }
 
 </script>
 
<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>

<style> 
.hover-image {
   display: none;
}

.box:hover .default-image {
   display: none;
}

.box:hover .hover-image {
   display: block;
}
.price-filter-form-container {
    position: absolute;
    top: 150px;
    left: 20px;
    padding: 20px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.price-filter-form-container form {
    margin-bottom: 20px;
}

label {
    font-size: 18px;
    margin-bottom: 10px;
}

.slider {
    width: 100%;
    margin-bottom: 20px;
}

p {
    font-size: 16px;
    margin-bottom: 10px;
}

#selected_value {
    color: #3498db;
    font-weight: bold;
}

input[name="price-filter-submit-button"] {
    width: 100%;
    background-color: #3498db;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

input[name="price-filter-submit-button"]:hover {
    background-color: var(--black);
}

  </style> 
</html>