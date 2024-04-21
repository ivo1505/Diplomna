<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:admin_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="search-form">
<h1 class="heading">Търсене на Администраторски акаунти</h1>
   <form action="" method="post">
      <input type="text" name="search_box" placeholder="търси тук..." maxlength="100" class="box" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>
</section>

<section class="accounts">

   <h1 class="heading">Администраторски акаунти</h1>

   <div class="box-container">

   <div class="box">
      <p>Добави нов админ</p>
      <a href="register_admin.php" class="option-btn">Регистрация</a>
   </div>

   <?php
   if (isset($_POST['search_btn'])) {
      $search_box = $_POST['search_box'];
      $select_admins = $conn->prepare("SELECT * FROM `admins` WHERE name LIKE :search_box");
      $select_admins->bindValue(':search_box', '%' . $search_box . '%', PDO::PARAM_STR);
      $select_admins->execute();
   } else {
      // If search button is not pressed, select all users
      $select_admins = $conn->prepare("SELECT * FROM `admins`");
      $select_admins->execute();
  }
  
  if($select_admins->rowCount() > 0){
   while($fetch_accounts = $select_admins->fetch(PDO::FETCH_ASSOC)){

   ?>
   <div class="box">
      <b><p> Администраторски код: <span></b><?= $fetch_accounts['id']; ?></span> </p>
      <b><p> Администраторско име: <span></b><?= $fetch_accounts['name']; ?></span> </p>
      <div class="flex-btn">
         <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Изтрий акаунт?')" class="delete-btn">Изтрий</a>
         <?php
            if($fetch_accounts['id'] == $admin_id){
               echo '<a href="update_profile.php" class="option-btn">поднови</a>';
            }
         ?>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Няма резултати за търсенето или няма налични акаунти с това име!</p>';
      }
   ?>

   </div>

</section>

<script src="../js/admin_script.js"></script>
   
<style>
   .search-form {
            text-align: center;
            
        }

        form {
            display: inline-block;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
</style>


</body>
</html>