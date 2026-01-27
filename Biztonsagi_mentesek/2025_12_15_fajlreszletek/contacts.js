// Kontakt űrlap validációja
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.querySelector('.contacts-form');
    
    if (contactForm) {
        // Form submit eseménykezelő
        contactForm.addEventListener('submit', function(e) {
            const name = document.getElementById('contacts-name').value.trim();
            const email = document.getElementById('contacts-email').value.trim();
            const category = document.getElementById('contacts-category').value;
            const message = document.getElementById('contacts-message').value.trim();
            
            // Ellenőrzések
            let errors = [];
            
            // Név ellenőrzés
            if (!name) {
                errors.push("Név megadása kötelező!");
            } else if (name.length < 2) {
                errors.push("A névnek legalább 2 karakter hosszúnak kell lennie!");
            }
            
            // Email ellenőrzés
            if (!email) {
                errors.push("Email cím megadása kötelező!");
            } else if (!isValidEmail(email)) {
                errors.push("Érvényes email címet adj meg!");
            }
            
            // Kategória ellenőrzés
            if (!category) {
                errors.push("Kategória kiválasztása kötelező!");
            }
            
            // Üzenet ellenőrzés
            if (!message) {
                errors.push("Üzenet megadása kötelező!");
            } else if (message.length < 10) {
                errors.push("Az üzenetnek legalább 10 karakter hosszúnak kell lennie!");
            }
            
            // reCAPTCHA ellenőrzés
            if (typeof grecaptcha !== 'undefined') {
                const recaptchaResponse = grecaptcha.getResponse();
                if (recaptchaResponse.length === 0) {
                    errors.push("Kérjük, erősítsd meg, hogy nem vagy robot!");
                }
            }
            
            // Ha vannak hibák, megjelenítjük
            if (errors.length > 0) {
                e.preventDefault();
                
                // Távolítsuk el a korábbi hibaüzeneteket, ha vannak
                const existingErrorAlert = document.querySelector('.client-side-error');
                if (existingErrorAlert) {
                    existingErrorAlert.remove();
                }
                
                // Hibaüzenet létrehozása
                const errorAlert = document.createElement('div');
                errorAlert.className = 'uzenet uzenet-hiba client-side-error';
                errorAlert.style.margin = '20px 0';
                errorAlert.innerHTML = `
                    <h3>Hiba a küldés előtt:</h3>
                    <ul>
                        ${errors.map(error => `<li>${error}</li>`).join('')}
                    </ul>
                `;
                
                // Beszúrás a form elé
                contactForm.parentNode.insertBefore(errorAlert, contactForm);
                
                // Görgetés a hibaüzenethez
                errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                return false;
            }
            
            return true;
        });
    }
    
    // Email validáció függvény
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // FAQ kezelés
    const faqItems = document.querySelectorAll('.contacts-faq-details');
    
    if (faqItems.length > 0) {
        // Csak egy FAQ item legyen nyitva egyszerre
        faqItems.forEach(item => {
            item.addEventListener('toggle', function() {
                if (this.open) {
                    faqItems.forEach(otherItem => {
                        if (otherItem !== this && otherItem.open) {
                            otherItem.open = false;
                        }
                    });
                }
            });
        });
    }
    
    // Ha van sikeres üzenet, görgetünk hozzá
    const successMessage = document.querySelector('.uzenet-siker');
    if (successMessage) {
        setTimeout(() => {
            successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 300);
    }
});