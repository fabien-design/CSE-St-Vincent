let slideIndex = 1;
let timer = setInterval(() => plusSlides(1), 5000); // Ajout du timer pour défilement automatique

showSlides(slideIndex);

function plusSlides(n) {
  clearInterval(timer); // Arrêt du timer lorsqu'on clique sur les flèches
  timer = setInterval(() => plusSlides(1), 5000);
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  clearInterval(timer); // Arrêt du timer lorsqu'on clique sur les points
  timer = setInterval(() => plusSlides(1), 5000);
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}



function transitionAfterPageLoad() {
  document.getElementById("body").classList.remove("no-transition");
}

// call the function inside an Immediately Invoked Function Expression (IIFE) to invoke it immediately after page load
(function() {
  transitionAfterPageLoad();
})()

// jQuery 
$(function() {
  $("#body").removeClass("no-transition");
});