const wrapper = document.querySelector('.cards-wrapper');
const cards = Array.from(document.querySelectorAll('.job-card'));
const prevBtn = document.querySelector('.balra');
const nextBtn = document.querySelector('.jobbra');

const visibleCount = 7;
let startIndex = 0;

function updateSlider() {
  const totalCards = cards.length;
  const maxIndex = Math.max(0, totalCards - visibleCount);

  if (startIndex > maxIndex) startIndex = maxIndex;
  if (startIndex < 0) startIndex = 0;

  const translateX = -(startIndex * (100 / visibleCount));
  wrapper.style.transform = `translateX(${translateX}%)`;

  prevBtn.style.display = startIndex === 0 ? 'none' : 'block';
  nextBtn.style.display = startIndex >= maxIndex ? 'none' : 'block';
}

nextBtn.addEventListener('click', () => {
  startIndex += visibleCount;
  updateSlider();
});

prevBtn.addEventListener('click', () => {
  startIndex -= visibleCount;
  updateSlider();
});

updateSlider();
