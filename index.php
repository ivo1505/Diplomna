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
   <meta name="author" content="Ивелин Беловеждов">
   <meta name="robots" content="index, follow">
   <!-- <meta name="keywords" content="ключова_дума1, ключова_дума2, ключова_дума3">
   <meta name="description" content="Кратко и съдържателно описание на уеб страницата."> -->

   <title>index</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="home-bg">

<section class="home">
<!-- Auto slider !spira da bachka ako potrebitelq cukne strelkite -->

<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

   <div class="swiper home-slider">
   
   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/Yeezy.png" alt="">
         </div>
         <div class="content">
            <span>Последни модели</span>
            <h3>Yeezy</h3>
            <a href="shop.php" class="btn">Пазарувай!</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/Air Jordan.png" alt="">
         </div>
         <div class="content">
            <span>Последни модели</span>
            <h3>Air Jordan</h3>
            <a href="shop.php" class="btn">Пазарувай!</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/Puma.png" alt="">
         </div>
         <div class="content">
            <span>Последни модели</span>
            <h3>Puma</h3>
            <a href="shop.php" class="btn">Пазарувай!</a>
         </div>
      </div>

   </div>
   <div class="swiper-button-next"></div>
   <div class="swiper-button-prev"></div>
      <div class="swiper-pagination"></div>

   </div>

</section>

</div>

<section class="category">

   <h1 class="heading">пазарувайте по категория</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <a href="category.php?brand=Air Jordan" class="swiper-slide slide">
      <img src="images/CategoriImage.jpg" alt="">
      <h3>Air Jordan</h3>
   </a>

   <a href="category.php?brand=Air Max" class="swiper-slide slide">
      <img src="images/CategoriImage2.png" alt="">
      <h3>Air Max</h3>
   </a>

   <a href="category.php?brand=Adidas" class="swiper-slide slide">
      <img src="images/CategoriImage3.png" alt="">
      <h3>Adidas</h3>
   </a>

   <a href="category.php?brand=Puma" class="swiper-slide slide">
      <img src="images/CategoriImage4.jpg" alt="">
      <h3>Puma</h3>
   </a>

   <a href="category.php?brand=Vans" class="swiper-slide slide">
      <img src="images/CategoriImage5.png" alt="">
      <h3>Vans</h3>
   </a>

   </div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">нашите продукти</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="swiper-slide slide">
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
   <div class="swiper-button-next"></div>
   <div class="swiper-button-prev"></div>
   <?php
      }
   }else{
      echo '<p class="empty">Все още няма добавени продукти!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

var swiper = new Swiper('.swiper-container', {
    // Добавете параметрите на вашите настройки на Swiper тук, ако е необходимо
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });
</script>

<style>label {
   font-size: 16px; /* Можете да промените 16px на желания от вас размер */
}  select {
        font-size: 16px; /* Можете да промените 16px на желания от вас размер */
    }
   </style>
</body>


</html>