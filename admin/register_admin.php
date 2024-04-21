<?php

include '../components/connect.php';

session_start();

// $admin_id = $_SESSION['admin_id'];

// if(!isset($admin_id)){
//    header('location:admin_login.php');
// }

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
   $select_admin->execute([$name]);

   if($select_admin->rowCount() > 0){
      $message[] = 'Името вече съществува!';
   }else{
      if($pass != $cpass){
         $message[] = 'Потвърдената парола не съвпада!';
      }else{
         $insert_admin = $conn->prepare("INSERT INTO `admins`(name, password) VALUES(?,?)");
         $insert_admin->execute([$name, $cpass]);
         $message[] = 'Успешна регистрация!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register admin</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<section class="form-container">

   <form action="" method="post">
      <h3>Регистрация</h3>
      <input type="text" name="name" required placeholder="Въведи име" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Въведи парола" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Потвърди паролата" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <p><span onclick="togglePasswordVisibility()">Показване на паролата?👁️</span></p>
      <div style = "text-align:center;">
      <input type="submit" value="Регистриране сега" class="btn" name="submit"><br>
      <p>Вече имаш акаунт?</p>
      <a class = "btn" href="admin_login.php">Вписване</a>
   </form>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>

<script>
  function togglePasswordVisibility() {
    var passwordInput = document.getElementsByName("pass")[0];
    var confirmInput = document.getElementsByName("cpass")[0];

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        confirmInput.type = "text";
    } else {
        passwordInput.type = "password";
        confirmInput.type = "password";
    }
}
</script>