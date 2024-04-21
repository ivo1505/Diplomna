
let navbar = document.querySelector('.header .flex .navbar');
let profile = document.querySelector('.header .flex .profile');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   profile.classList.remove('active');
}

let mainImage = document.querySelector('.quick-view .box .row .image-container .main-image img');
let subImages = document.querySelectorAll('.quick-view .box .row .image-container .sub-image img');

subImages.forEach(images =>{
   images.onclick = () =>{
      src = images.getAttribute('src');
      mainImage.src = src;
   }
});

src="https://unpkg.com/swiper/swiper-bundle.min.js"
document.addEventListener("DOMContentLoaded", function () {
   var mySwiper = new Swiper(".home-slider", {
     loop: true,
     autoplay: {
       delay: 2000,
     },
     pagination: {
       el: ".swiper-pagination",
       clickable: true,
     },
     navigation: {
       nextEl: ".swiper-button-next",
       prevEl: ".swiper-button-prev",
     },
     // Добавете тази опция, за да позволите автоматичното превключване
     // да продължи след клик на стрелките
     allowTouchMove: false,
     disableOnInteraction: false,
   });
 });

     // Проверка дали има запазени данни в localStorage и възстановяване на тях

   // Функция за изтриване на информацията от localStorage
   function clearCustomLocalStorage() {
      localStorage.removeItem('checkoutData');
      localStorage.removeItem('promoCode');
    }
    document.getElementById('orderForm').addEventListener('submit', function(event) {
      var paymentMethod = document.getElementById('orderMethod').value;
      if (paymentMethod === 'наложен платеж') {
         // Не правим нищо, формата ще се изпрати към същата страница
      } else if (paymentMethod === 'paypal') {
         // Променяме action атрибута на формата
         document.getElementById('orderForm').action = 'paypal.php';
      }
   });