<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_user->execute([$delete_id]);
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_orders->execute([$delete_id]);
   $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
   $delete_messages->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:users_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>users accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="search-form">
<h1 class="heading">Търсене на Клиентски акаунти</h1>
   <form action="" method="post">
      <input type="text" name="search_box" placeholder="търси тук..." maxlength="100" class="box" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>
</section>

<section class="accounts">

   <h1 class="heading">Клиентски акаунти</h1>

   <div class="box-container">
   <?php
if (isset($_POST['search_btn'])) {
    $search_box = $_POST['search_box'];
    $select_users = $conn->prepare("SELECT * FROM `users` WHERE name LIKE :search_box");
    $select_users->bindValue(':search_box', '%' . $search_box . '%', PDO::PARAM_STR);
    $select_users->execute();
} else {
    // If search button is not pressed, select all users
    $select_users = $conn->prepare("SELECT * FROM `users`");
    $select_users->execute();
}

if ($select_users->rowCount() > 0) {
    while ($fetch_accounts = $select_users->fetch(PDO::FETCH_ASSOC)) {
?>
        <div class="box">
            <b><p> Клиентски код : <span><?= $fetch_accounts['id']; ?></span> </p></b>
            <b><p> Клиентско име : <span><?= $fetch_accounts['name']; ?></span> </p></b>
            <b><p> Имейл : <span><?= $fetch_accounts['email']; ?></span> </p></b>
            <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Искате да изтриете този акаунт? Свързаната с потребителя информация също ще бъде изтрита!')" class="delete-btn">Изтрий</a>
        </div>
<?php
    }
} else {
    // Display message when there are no search results or no users
    echo '<p class="empty">Няма резултати за търсенето или няма налични акаунти!</p>';
}
?>


   </div>

</section>

<script src="../js/admin_script.js"></script>
   

<style>
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