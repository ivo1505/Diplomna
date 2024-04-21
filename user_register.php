<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email,]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'Имейлът вече съществува!';
   }else{
      if($pass != $cpass){
         $message[] = 'Потвърдената парола не съвпада!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $message[] = 'Регистриран успешно, влезте сега, моля!';
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
   <title>register</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>
<script src="js/script.js"></script>

<section class="form-container">

   <form action="" method="post">
      <h3>Регистриране</h3>
      <input type="text" name="name" required placeholder="Въведи потребителско име" maxlength="20"  class="box">
      <input type="email" name="email" required placeholder="Въведи имейл" maxlength="50"  class="box">
      <input type="password" name="pass" required placeholder="Въведи парола" maxlength="20"  class="box">
      <input type="password" name="cpass" required placeholder="Потвърди паролата" maxlength="20"  class="box">
      <p><span onclick="togglePasswordVisibility()">Показване на паролата?👁️</span></p>
      <input type="submit" value="Регистрация сега" class="btn" name="submit">
      <p>Вече имаш акаунт?</p>
      <a href="user_login.php" class="option-btn">Вписване</a>
   </form>

</section>

<?php include 'components/footer.php'; ?>


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