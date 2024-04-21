<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'Вече е изпратено съобщение!';
   }else{

      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);

      $message[] = 'Успешно изпратено съобщение!';

   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="contact">

   <form action="" method="post">
      <h3>връзка с нас</h3>
      <input type="text" name="name" placeholder="Въведи име" required maxlength="20" class="box">
      <input type="email" name="email" placeholder="Въведи имейл" required maxlength="50" class="box">
      <input type="number" name="number" min="0" max="9999999999" placeholder="Въведи тел. номер" required onkeypress="if(this.value.length == 10) return false;" class="box">
      <textarea name="msg" class="box" placeholder="Въведи твоето съобщение" cols="30" rows="10"></textarea>
      <input type="submit" value="Изпрати" name="send" class="btn">
   </form>
   <div class="direct-contact-container">

<ul class="contact-list">

<br><br><li class="list-item"><b class=" fa-2x"><span class="contact-text">Контакти:</span></b></li><br>

  <br><li class="list-item"><i class="fa fa-map-marker fa-2x"><span class="contact-text place">Plovdiv, Bulgaria</span></i></li><br>
  
  <li class="list-item"><i class="fa fa-phone fa-2x"><span class="contact-text phone"><a href="#" title="Give me a call">0899961516</a></span></i></li><br>

  <li class="list-item"><i class="fa fa-phone fa-2x"><span class="contact-text phone"><a href="#" title="Give me a call">0878806215</a></span></i></li><br>
  
  <li class="list-item"><i class="fa fa-envelope fa-2x"><span class="contact-text gmail"><a href="#" title="Send me an email">ivakabg05@gmail.com</a></span></i></li><br>

  <li class="list-item"><i class="fa fa-envelope fa-2x"><span class="contact-text gmail"><a href="#" title="Send me an email">ivelin__05@abv.bg</a></span></i></li>

</ul>

</div>

</section>

<iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d1460.349374669302!2d24.730085967025918!3d42.14106816836836!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1z0L_Qs9C10LU!5e0!3m2!1sbg!2sbg!4v1702739913617!5m2!1sbg!2sbg" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>