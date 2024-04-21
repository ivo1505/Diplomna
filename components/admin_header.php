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

   <link rel="stylesheet" href="../css/admin_style.css">

   <title>Document</title>

</head>
<body>
   
<header class="header">

<section class="flex">

   <a href="../admin/dashboard.php" class="logo">FootX <span>Admin</span></a>

   <nav class="navbar">
      <!-- <button><a href="../admin/dashboard.php">Home</a></button> -->
      <button><a href="../admin/products.php">Добави продукт</a></button>
      <button><a href="../admin/productsfunc.php">Налични продуктите</a></button>
      <button><a href="../admin/placed_orders.php">Поръчки</a></button>
      <button><a href="../admin/admin_accounts.php">Админи</a></button>
      <button><a href="../admin/users_accounts.php">Налични Клиенти</a></button>
      <button><a href="../admin/messages.php">Съобщения</a></button>
      
   </nav>

   <div class="icons">
      <div id="menu-btn" class="fas fa-bars"></div>
      <div id="user-btn" class="fas fa-user"></div>
   </div>

   <div class="profile">
      <?php
         $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
         $select_profile->execute([$admin_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <b><p>Администраторско име:</b> <?= $fetch_profile['name']; ?></p>
      <a href="../admin/update_profile.php" class="btn">Обнови акаунт</a>
      <!-- <div class="flex-btn">
         <a href="../admin/register_admin.php" class="option-btn">register</a>
         <a href="../admin/admin_login.php" class="option-btn">login</a>
      </div> -->
      <a href="../components/admin_logout.php" class="delete-btn" onclick="return confirm('Сигурни ли сте, че искате да излезнете от профила си?');">Излизане</a> 
   </div>

</section>

</header>

</body>

</html>
