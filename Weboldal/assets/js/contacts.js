document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.querySelector('.contacts-form');
    const messageTextarea = document.getElementById('contacts-message');
    const charCount = document.getElementById('charCount');
    const charCounter = document.getElementById('charCounter');

    if (messageTextarea && charCount && charCounter) {
        updateCharCounter();
        
        messageTextarea.addEventListener('input', updateCharCounter);
        messageTextarea.addEventListener('keyup', updateCharCounter);
        messageTextarea.addEventListener('change', updateCharCounter);
        
        messageTextarea.addEventListener('paste', function(e) {
            setTimeout(updateCharCounter, 10);
        });
        
        function updateCharCounter() {
            const length = messageTextarea.value.length;
            charCount.textContent = length;
            
            if (length > 4900) {
                charCounter.classList.add('long');
                if (length >= 5000) {
                    charCount.textContent = '5000+';
                }
            } else {
                charCounter.classList.remove('long');
            }
        }
    }
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const name = document.getElementById('contacts-name').value.trim();
            const email = document.getElementById('contacts-email').value.trim();
            const category = document.getElementById('contacts-category').value;
            const message = document.getElementById('contacts-message').value.trim();
            
            let errors = [];
            
            if (!name) {
                errors.push("Név megadása kötelező!");
            } else if (name.length < 2) {
                errors.push("A névnek legalább 2 karakter hosszúnak kell lennie!");
            }
            
            if (!email) {
                errors.push("Email cím megadása kötelező!");
            } else if (!isValidEmail(email)) {
                errors.push("Érvényes email címet adj meg!");
            }
            
            if (!category) {
                errors.push("Kategória kiválasztása kötelező!");
            }
            
            if (!message) {
                errors.push("Üzenet megadása kötelező!");
            } else if (message.length < 10) {
                errors.push("Az üzenetnek legalább 10 karakter hosszúnak kell lennie!");
            }
            
            if (typeof grecaptcha !== 'undefined') {
                const recaptchaResponse = grecaptcha.getResponse();
                if (recaptchaResponse.length === 0) {
                    errors.push("Kérjük, erősítsd meg, hogy nem vagy robot!");
                }
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                
                const existingErrorAlert = document.querySelector('.client-side-error');
                if (existingErrorAlert) {
                    existingErrorAlert.remove();
                }
                
                const errorAlert = document.createElement('div');
                errorAlert.className = 'uzenet uzenet-hiba client-side-error';
                errorAlert.style.margin = '20px 0';
                errorAlert.innerHTML = `
                    <h3>Hiba a küldés előtt:</h3>
                    <ul>
                        ${errors.map(error => `<li>${error}</li>`).join('')}
                    </ul>
                `;
                
                contactForm.parentNode.insertBefore(errorAlert, contactForm);
                
                errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                return false;
            }
            
            return true;
        });
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    const faqItems = document.querySelectorAll('.contacts-faq-details');
    
    if (faqItems.length > 0) {
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
    
    const successMessage = document.querySelector('.uzenet-siker');
    if (successMessage) {
        setTimeout(() => {
            successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 300);
    }
});


document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.querySelector('.contacts-message-success');
    if (successMessage) {
        const form = document.querySelector('.contacts-form');
        if (form) {
            form.reset();
            
            const charCount = document.getElementById('charCount');
            if (charCount) {
                charCount.textContent = '0';
            }
            
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.reset();
            }
        }
        
        setTimeout(() => {
            successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 300);
    }
});