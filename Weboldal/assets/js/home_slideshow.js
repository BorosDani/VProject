document.addEventListener('DOMContentLoaded', function() {
    const slideshowContainer = document.querySelector('.slideshow-container');
    
    if (!slideshowContainer) return;
    
    let currentSlideIndex = 0;
    let slideInterval;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.slide-nav.prev');
    const nextBtn = document.querySelector('.slide-nav.next');

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        slides[index].classList.add('active');
        dots[index].classList.add('active');
        
        currentSlideIndex = index;
    }

    function changeSlide(direction) {
        let newIndex = currentSlideIndex + direction;
        
        if (newIndex >= slides.length) {
            newIndex = 0;
        } else if (newIndex < 0) {
            newIndex = slides.length - 1;
        }
        
        showSlide(newIndex);
        resetInterval();
    }

    function currentSlide(index) {
        showSlide(index);
        resetInterval();
    }

    function resetInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(() => {
            changeSlide(1);
        }, 5000);
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => changeSlide(-1));
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', () => changeSlide(1));
    }

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => currentSlide(index));
    });

    showSlide(currentSlideIndex);
    slideInterval = setInterval(() => {
        changeSlide(1);
    }, 5000);
    
    slideshowContainer.addEventListener('mouseenter', () => {
        clearInterval(slideInterval);
    });
    
    slideshowContainer.addEventListener('mouseleave', () => {
        resetInterval();
    });
    
    slideshowContainer.addEventListener('touchstart', () => {
        clearInterval(slideInterval);
    });
    
    slideshowContainer.addEventListener('touchend', () => {
        resetInterval();
    });
});