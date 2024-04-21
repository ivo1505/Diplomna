<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:index.php');
   }else{
      $message[] = 'Невалидни имейл или парола!';
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
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Вписване</h3>
      <input type="email" name="email" required placeholder="Въведи имейл" maxlength="50"  class="box">
      <input type="password" name="pass" required placeholder="Въведи парола" maxlength="20"  class="box">
      <p><span onclick="togglePasswordVisibility()">Показване на паролата?👁️</span></p>
      <input type="submit" value="Вписване сега" class="btn" name="submit">
      <p>Нямаш акаунт?</p>
      <a href="user_register.php" class="option-btn">Регистрация</a>
   </form>

</section>
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

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>