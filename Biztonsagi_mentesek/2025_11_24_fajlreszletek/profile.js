// Profilkép előnézet
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('profile_image_preview').src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Változások követése
let formModified = false;
let modifiedFieldsCount = 0;

function setFormModified(state, count = 0) {
    formModified = state;
    modifiedFieldsCount = count;
    
    // Frissítjük a folyamatban lévő módosításokat
    updateFolyamatbanUzenet(count);
}

function checkFormChanges() {
    const form = document.getElementById('profileForm');
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, select, textarea');
    let changedCount = 0;
    
    inputs.forEach(input => {
        if (input.type === 'file') {
            if (input.files.length > 0) {
                changedCount++;
            }
        } else if (input.defaultValue !== input.value) {
            changedCount++;
        }
    });
    
    setFormModified(changedCount > 0, changedCount);
    return changedCount > 0;
}

function updateFolyamatbanUzenet(count) {
    // Eltávolítjuk a régi folyamatban üzenetet
    const regiFolyamatban = document.querySelector('.folyamatban');
    if (regiFolyamatban) {
        regiFolyamatban.remove();
    }
    
    // Ha van módosítás, hozzáadjuk az új üzenetet
    if (count > 0) {
        const folyamatbanDiv = document.createElement('div');
        folyamatbanDiv.className = 'folyamatban';
        folyamatbanDiv.innerHTML = `<p>Módosítás alatt: ${count} mező</p>`;
        
        // Beszúrjuk a hiba/siker üzenetek után
        const sikerDiv = document.querySelector('.siker');
        const hibaDiv = document.querySelector('.hiba');
        
        if (sikerDiv) {
            sikerDiv.after(folyamatbanDiv);
        } else if (hibaDiv) {
            hibaDiv.after(folyamatbanDiv);
        } else {
            // Ha nincs hiba/siker üzenet, a profil információk után szúrjuk be
            const profileInfo = document.querySelector('.profile-header-container');
            profileInfo.after(folyamatbanDiv);
        }
    }
}

// Oldal elhagyásának figyelése
function handleBeforeUnload(event) {
    if (formModified) {
        event.preventDefault();
        event.returnValue = `Nem mentett módosításaid vannak a profilodban (${modifiedFieldsCount} mező). Biztosan el akarod hagyni az oldalt?`;
        return event.returnValue;
    }
}

// Link kattintások kezelése
function handleLinkClick(event) {
    if (formModified) {
        const confirmation = confirm(`Nem mentett módosításaid vannak a profilodban (${modifiedFieldsCount} mező). Biztosan el akarod hagyni az oldalt? A módosításaid elvesznek.`);
        if (!confirmation) {
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
    }
}

// Város autocomplete funkció
function initCityAutocomplete() {
    const varosInput = document.getElementById('varos');
    const autocompleteContainer = document.getElementById('varos-autocomplete');
    
    if (!varosInput || !autocompleteContainer) return;
    
    // Városok listája (ugyanaz mint PHP-ban)
    const varosok = [
        'Budapest', 'Debrecen', 'Szeged', 'Miskolc', 'Pécs', 'Győr', 'Nyíregyháza', 'Kecskemét', 'Székesfehérvár',
        'Szombathely', 'Érd', 'Tatabánya', 'Sopron', 'Kaposvár', 'Veszprém', 'Békéscsaba', 'Zalaegerszeg', 'Eger',
        'Nagykanizsa', 'Dunakeszi', 'Hódmezővásárhely', 'Szeged', 'Cegléd', 'Ózd', 'Baja', 'Salgótarján', 'Vác',
        'Gödöllő', 'Szekszárd', 'Pápa', 'Gyöngyös', 'Kazincbarcika', 'Ajka', 'Orosháza', 'Szolnok', 'Szentes',
        'Esztergom', 'Jászberény', 'Komló', 'Dunaújváros', 'Makó', 'Kiskunfélegyháza', 'Kiskunhalas', 'Kiskőrös',
        'Kisvárda', 'Körmend', 'Kőszeg', 'Lenti', 'Mátészalka', 'Mohács', 'Mosonmagyaróvár', 'Nagyatád', 'Pécsvárad',
        'Sárbogárd', 'Sarkad', 'Sásd', 'Siklós', 'Sümeg', 'Szarvas', 'Tata', 'Tiszakécske', 'Tiszavasvári', 'Tolna',
        'Várpalota', 'Vásárosnamény', 'Záhony'
    ];
    
    let selectedIndex = -1;
    let filteredCities = [];
    
    function showSuggestions() {
        const input = varosInput.value.toLowerCase();
        filteredCities = varosok.filter(city => 
            city.toLowerCase().includes(input)
        );
        
        if (input.length > 0 && filteredCities.length > 0) {
            autocompleteContainer.innerHTML = filteredCities
                .map(city => `<div class="autocomplete-suggestion">${city}</div>`)
                .join('');
            autocompleteContainer.style.display = 'block';
        } else {
            autocompleteContainer.style.display = 'none';
        }
        selectedIndex = -1;
    }
    
    function selectSuggestion(index) {
        const suggestions = autocompleteContainer.querySelectorAll('.autocomplete-suggestion');
        
        // Eltávolítjuk az összes aktív osztályt
        suggestions.forEach(suggestion => suggestion.classList.remove('active'));
        
        if (index >= 0 && index < suggestions.length) {
            suggestions[index].classList.add('active');
            selectedIndex = index;
        }
    }
    
    function useSuggestion(index) {
        if (index >= 0 && index < filteredCities.length) {
            varosInput.value = filteredCities[index];
            autocompleteContainer.style.display = 'none';
            selectedIndex = -1;
            checkFormChanges(); // Form változás ellenőrzése
        }
    }
    
    // Eseménykezelők
    varosInput.addEventListener('input', function() {
        showSuggestions();
        checkFormChanges();
    });
    
    varosInput.addEventListener('focus', showSuggestions);
    
    varosInput.addEventListener('keydown', function(e) {
        const suggestions = autocompleteContainer.querySelectorAll('.autocomplete-suggestion');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = (selectedIndex + 1) % suggestions.length;
            selectSuggestion(selectedIndex);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = (selectedIndex - 1 + suggestions.length) % suggestions.length;
            selectSuggestion(selectedIndex);
        } else if (e.key === 'Enter' && selectedIndex >= 0) {
            e.preventDefault();
            useSuggestion(selectedIndex);
        } else if (e.key === 'Escape') {
            autocompleteContainer.style.display = 'none';
            selectedIndex = -1;
        }
    });
    
    // Kattintás a javaslatokra
    autocompleteContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('autocomplete-suggestion')) {
            const index = Array.from(autocompleteContainer.querySelectorAll('.autocomplete-suggestion'))
                .indexOf(e.target);
            useSuggestion(index);
        }
    });
    
    // Kattintás az oldal más részére elrejti a javaslatokat
    document.addEventListener('click', function(e) {
        if (e.target !== varosInput && !autocompleteContainer.contains(e.target)) {
            autocompleteContainer.style.display = 'none';
        }
    });
    
    // Touch események mobilon
    varosInput.addEventListener('touchstart', function() {
        // Mobilon a focus esemény nem mindig aktiválódik, ezért itt is meghívjuk
        setTimeout(showSuggestions, 100);
    });
}

// Inicializálás
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    if (!form) return;
    
    // Profilkép eseménykezelők - dupla kattintás javítása
    const profilePic = document.querySelector('.profile_pic');
    const editIcon = document.querySelector('.edit_icon');
    const fileInput = document.getElementById('profilkep');
    
    // Csak a ceruza ikonra kattintás nyissa meg a fájlválasztót
    if (editIcon) {
        editIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            fileInput.click();
        });
    }
    
    // A profilkép többi részére kattintva is megnyílik a fájlválasztó
    if (profilePic) {
        profilePic.addEventListener('click', function(e) {
            if (!e.target.closest('.edit_icon')) {
                fileInput.click();
            }
        });
    }
    
    // Profilkép változás kezelése
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            previewImage(this);
            checkFormChanges();
        });
    }
    
    // Alapértelmezett értékek beállítása
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (input.type !== 'file') {
            input.defaultValue = input.value;
        }
    });
    
    // Eseményfigyelők
    inputs.forEach(input => {
        input.addEventListener('input', checkFormChanges);
        input.addEventListener('change', checkFormChanges);
    });
    
    // Form reset
    form.addEventListener('reset', function() {
        setTimeout(() => {
            setFormModified(false, 0);
            // Alapértelmezett értékek frissítése
            inputs.forEach(input => {
                if (input.type !== 'file') {
                    input.defaultValue = input.value;
                }
            });
        }, 0);
    });
    
    // Form submit
    form.addEventListener('submit', function() {
        setFormModified(false, 0);
    });
    
    // Navigáció figyelése
    window.addEventListener('beforeunload', handleBeforeUnload);
    
    // Linkek figyelése
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link && link.href && !link.href.includes('javascript:')) {
            handleLinkClick(e);
        }
    });
    
    // Visszaállítás gomb
    const resetBtn = document.querySelector('.reset-btn');
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            if (formModified) {
                const confirmation = confirm(`Biztosan vissza szeretnéd állítani az összes módosítást? (${modifiedFieldsCount} mező)`);
                if (!confirmation) {
                    e.preventDefault();
                }
            }
        });
    }
    
    // Város autocomplete inicializálása
    initCityAutocomplete();
});