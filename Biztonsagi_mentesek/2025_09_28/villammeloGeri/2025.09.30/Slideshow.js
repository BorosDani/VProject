const slider = document.querySelector(".slider");
const slides = document.querySelectorAll(".slider img");
const dots = document.querySelectorAll(".slider-nav a");

let index = 0;

function showSlide(i) {
  slider.style.transform = `translateX(${-i * 100}%)`;

  if (dots.length) {
    dots.forEach(dot => dot.classList.remove("active"));
    dots[i].classList.add("active");
  }
}

function nextSlide() {
  index = (index + 1) % slides.length;
  showSlide(index);
}

// Indítás
showSlide(index);
setInterval(nextSlide, 3000); // 3 másodpercenként vált
