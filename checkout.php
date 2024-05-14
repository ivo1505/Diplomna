<?php

include 'components/connect.php';
echo '<script src="js/script.js"></script>';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = 'flat no. '. $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      echo '<script>clearCustomLocalStorage();</script>';

      $message[] = 'Успешно направена поръчка!';
   
   }else{
      $message[] = 'Количката ви е празна!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

   <form id="orderForm" action="" method="post">

   <h3>Твоята поръчка</h3>

      <div class="display-orders">
         
      <?php

      $grand_total = 0;
      $cart_items = array(); 
      $select_cart = $conn->prepare("SELECT *, size FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if ($select_cart->rowCount() > 0) {
        while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
         $cart_items[] = $fetch_cart['name'] . ' - (Размер: ' . $fetch_cart['size'] . ') - ' . '(' . $fetch_cart['price'] . '$' . ' x ' . $fetch_cart['quantity'] . ')';
         $total_products = implode($cart_items);
        $product_size = $fetch_cart['size'];
        $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
        ?>
        <p><?= $fetch_cart['name']; ?> <span>(<?= $fetch_cart['price'] . '$ x ' . $fetch_cart['quantity']. ', Размер: ' . $fetch_cart['size']?>)</span></p>

      <?php
            }
         }else{
            echo '<p class="empty">Твоята количка е празна!</p>';
         }
         if(isset($_POST['apply_promo_code']) && $_POST['promo_code'] === 'code') {
            $grand_total = $grand_total * 0.95; // 5% price reduction when promo code is applied.
            
        } else {
            $grand_total = $grand_total; // If no promo code is applied, the subtotal remains unchanged.
        }
        
      ?>
        
      <h3>Въведи своите данни</h3>

      <div class="flex">
         <div class="inputBox">
            <span><b>Две имена:</b></span>
            <input type="text" name="name" placeholder="Въведи две имена" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span><b>Тел. номер::</b></span>
            <input type="number" name="number" placeholder="Въведи тел. номер" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
         </div>
         <div class="inputBox">
            <span><b>Имейл:</b></span>
            <input type="email" name="email" placeholder="Въведи имейл" class="box" maxlength="50" required>
         </div>
            <div class = "inputBox">
            <span><b>Промо код:</b></span>
         <br><input type="text" class = "box" name="promo_code" placeholder="Въведете промо код 'code' за 5% отстъпка">
         <button   class = "btn" name="apply_promo_code">Приложи</button>
      </div>
         <div class="inputBox">
            <span><b>Адрес за доставка 01:</b></span>
            <input type="text" name="flat" placeholder="Въведи адрес за доставка 01" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span><b>Адрес за доставка 02:</b></span>
            <input type="text" name="street" placeholder="Въведи адрес за доставка 02" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span><b>Град:</b></span>
            <input type="text" name="city" placeholder="Въведи град" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span><b>Район:</b></span>
            <input type="text" name="state" placeholder="Въведи район" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span><b>Държава:</b></span>
            <input type="text" name="country" placeholder="Въведи държава" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span><b>Пощенски код:</b></span>
            <input type="number" min="0" name="pin_code" placeholder="Въведи пощенски код" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
         </div>
         <div class="inputBox">
            <span><b>Метод на плащане:</b></span>
            <select name="method" name="method" class="box" required>
               <option value="cash on delivery">наложен платеж</option>
               <option value="paypal">paypal</option>
            </select>
         </div>
      
      </div>
         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
         <div class="grand-total">Крайна цена: <span><?= $grand_total; ?>$</span></div>
      </div>

      <input type="hidden" name="total_amount" value="<?php echo $grand_total; ?>"> 
      <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="Поръчай">

   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.addEventListener('submit', function(event) {
            var paymentMethod = document.querySelector('select[name="method"]').value;
            if (paymentMethod === 'paypal') {
               // Change the action attribute of the PayPal form.
                document.getElementById('orderForm').action = 'paypal.php';
            }
        });
    }
});

      document.addEventListener("DOMContentLoaded", function() {
      var nameInput = document.querySelector('input[name="name"]');
      var numberInput = document.querySelector('input[name="number"]');
      var emailInput = document.querySelector('input[name="email"]');
      var flatInput = document.querySelector('input[name="flat"]');
      var streetInput = document.querySelector('input[name="street"]');
      var cityInput = document.querySelector('input[name="city"]');
      var stateInput = document.querySelector('input[name="state"]');
      var countryInput = document.querySelector('input[name="country"]');
      var pinCodeInput = document.querySelector('input[name="pin_code"]');
      var promoCodeInput = document.querySelector('input[name="promo_code"]');

      if (localStorage.getItem('checkoutData')) {
          var checkoutData = JSON.parse(localStorage.getItem('checkoutData'));

          nameInput.value = checkoutData.name || '';
          numberInput.value = checkoutData.number || '';
          emailInput.value = checkoutData.email || '';
          flatInput.value = checkoutData.flat || '';
          streetInput.value = checkoutData.street || '';
          cityInput.value = checkoutData.city || '';
          stateInput.value = checkoutData.state || '';
          countryInput.value = checkoutData.country || '';
          pinCodeInput.value = checkoutData.pin_code || '';
      }

      if (localStorage.getItem('promoCode')) {
          promoCodeInput.value = localStorage.getItem('promoCode');
      }
  });

  // Save the data to localStorage on input.
  document.addEventListener("input", function() {
      var checkoutData = {
          name: document.querySelector('input[name="name"]').value,
          number: document.querySelector('input[name="number"]').value,
          email: document.querySelector('input[name="email"]').value,
          flat: document.querySelector('input[name="flat"]').value,
          street: document.querySelector('input[name="street"]').value,
          city: document.querySelector('input[name="city"]').value,
          state: document.querySelector('input[name="state"]').value,
          country: document.querySelector('input[name="country"]').value,
          pin_code: document.querySelector('input[name="pin_code"]').value
      };

      localStorage.setItem('checkoutData', JSON.stringify(checkoutData));
  });

  // Save promo code value to localStorage on apply.
  document.querySelector('button[name="apply_promo_code"]').addEventListener("click", function() {
      var promoCode = document.querySelector('input[name="promo_code"]').value;
      localStorage.setItem('promoCode', promoCode);
  });

</script>
</body>

</html>