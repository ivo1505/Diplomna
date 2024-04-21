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
   <title>shop</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products" id="default-container">
    <h1 class="heading">Нашите продукти</h1>

    <div class="box-container products hidden" id="default-container">
        <?php
       if (isset($_POST['filter_brand'])) {
        $filter_brand = $_POST['filter_brand'];
        
        if ($filter_brand == 'Air Jordan') {
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE `brand` = 'AIR JORDAN'");
        } elseif ($filter_brand == 'Air Max') {
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE `brand` = 'AIR MAX'");
        } elseif ($filter_brand == 'Adidas') {
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE `brand` = 'Adidas'");
        } elseif ($filter_brand == 'Puma') {
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE `brand` = 'Puma'");
        } elseif ($filter_brand == 'Vans') {
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE `brand` = 'Vans'");
        } else {
            $select_products = $conn->prepare("SELECT * FROM `products`");
        }
    
        $select_products->execute();
    }
         elseif (isset($_POST['filter_gender'])) {
            $filter_gender = $_POST['filter_gender'];
            
            if ($filter_gender == 'male') {
                $select_products = $conn->prepare("SELECT * FROM `products` WHERE `gender` = 'мъже'");
            } elseif ($filter_gender == 'female') {
                $select_products = $conn->prepare("SELECT * FROM `products` WHERE `gender` = 'жени'");
            } else {
                $select_products = $conn->prepare("SELECT * FROM `products`");
            }
    
            $select_products->execute();
        } elseif (isset($_POST['filter_price'])) {
            $filter_price = $_POST['filter_price'];
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE `price` <= :filter_price");
            $select_products->bindParam(':filter_price', $filter_price, PDO::PARAM_INT);
            $select_products->execute();
        }else {
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
        }

        if ($select_products->rowCount() > 0) {
            while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
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
        } else {
            echo '<p class="empty">Няма намерени налични продукти!</p>';
        }
        ?>
    </div>
</section>

   <!-- Форма за филтър -->
   <div class="price-filter-form-container">
   <form method="post" action="">
      <label for="filter_price">Филтриране по цена:</label>
      <input type="range" min="1" max="1000" value="500" class="slider" name="filter_price" id="filter_price" step="1">
      <p>Избрана стойност: <span id="selected_value">500</span>$</p>
      <input type="submit" value="Филтрирай" name="price-filter-submit-button" onclick="toggleSection()">
   </form>
   </div>
   
   <div class="gender-filter-form-container">
   <form method="post" action="">
      <label for="filter_gender">Филтриране по пол:</label>
      <select name="filter_gender" id="filter_gender">
         <option value="male">Мъже</option>
         <option value="female">Жени</option>
      </select>
      <input type="submit" value="Филтрирай" name="gender-filter-submit-button">
   </form>
</div>

<div class="brand-filter-form-container">
   <form method="post" action="">
      <label for="filter_brand">Филтриране по марка:</label>
      <select name="filter_brand" id="filter_brand">
         <option value="Air Jordan">Air Jordan</option>
         <option value="Air Max">Air Max</option>
         <option value="Adidas">Adidas</option>
         <option value="Puma">Puma</option>
         <option value="Vans">Vans</option>
      </select>
      <input type="submit" value="Филтрирай" name="brand-filter-submit-button">
   </form>
</div>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script>
   
  var slider = document.getElementById("filter_price");
  var output = document.getElementById("selected_value");
  output.innerHTML = slider.value; // Display the default slider value

  // Update the current slider value (each time you drag the slider handle)
  slider.oninput = function() {
    output.innerHTML = this.value;
  }

</script>

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

.gender-filter-form-container {
    position: absolute;
    top: 370px;
    left: 20px;
    padding: 20px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.brand-filter-form-container form {
    margin-bottom: 20px;
}

.brand-filter-form-container {
    position: absolute;
    top: 500px;
    left: 20px;
    padding: 20px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.brand-filter-form-container form {
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
input[name="gender-filter-submit-button"] {
    display: block;
    width: 100%; /* Заема цялата ширина на наличния контейнер */
    background-color: #3498db;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

input[name="gender-filter-submit-button"]:hover {
    background-color: var(--black);
}
input[name="brand-filter-submit-button"] {
    display: block;
    width: 100%; /* Заема цялата ширина на наличния контейнер */
    background-color: #3498db;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

input[name="brand-filter-submit-button"]:hover {
    background-color: var(--black);
}
  </style> 

</body>
</html>