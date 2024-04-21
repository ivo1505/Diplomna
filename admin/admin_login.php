<?php

include '../components/connect.php';

session_start();

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);

   if($select_admin->rowCount() > 0){
      $_SESSION['admin_id'] = $row['id'];
      header('location:dashboard.php');
   }else{
      $message[] = 'Невалидни парола или име!';
   }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

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

<section class="form-container">

   <form action="" method="post">
      <h3>Вписване</h3>
      <input type="text" name="name" required placeholder="Въведи име" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Въведи парола" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <p><span onclick="togglePasswordVisibility()">Показване на паролата?👁️</span></p>
      <input type="submit" value="вписване сега" class="btn" name="submit"><br>
      <p>Нямаш акаунт?</p>
      <a class = "btn" href="register_admin.php">Регистрация</a>
   </form>

</section>
<!-- <script src="..js/admin_script.js"></script>
tva ne bachka trqbva da go opraish -->
</body>
</html>
<script>
   function togglePasswordVisibility() {
   var passwordInput = document.getElementsByName("pass")[0];
   if (passwordInput.type === "password") {
       passwordInput.type = "text";
   } else {
       passwordInput.type = "password";
   }
}
</script>