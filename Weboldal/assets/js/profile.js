function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('profile_image_preview').src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

let formModified = false;
let modifiedFieldsCount = 0;

function setFormModified(state, count = 0) {
    formModified = state;
    modifiedFieldsCount = count;
    
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
    const regiFolyamatban = document.querySelector('.folyamatban');
    if (regiFolyamatban) {
        regiFolyamatban.remove();
    }
    
    if (count > 0) {
        const folyamatbanDiv = document.createElement('div');
        folyamatbanDiv.className = 'folyamatban';
        folyamatbanDiv.innerHTML = `<p>Módosítás alatt: ${count} mező</p>`;
        
        const sikerDiv = document.querySelector('.siker');
        const hibaDiv = document.querySelector('.hiba');
        
        if (sikerDiv) {
            sikerDiv.after(folyamatbanDiv);
        } else if (hibaDiv) {
            hibaDiv.after(folyamatbanDiv);
        } else {
            const profileInfo = document.querySelector('.profile-header-container');
            profileInfo.after(folyamatbanDiv);
        }
    }
}

function handleBeforeUnload(event) {
    if (formModified) {
        event.preventDefault();
        event.returnValue = `Nem mentett módosításaid vannak a profilodban (${modifiedFieldsCount} mező). Biztosan el akarod hagyni az oldalt?`;
        return event.returnValue;
    }
}

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

function initCountyValidation() {
    const varmegyeSelect = document.getElementById('varmegye');
    if (!varmegyeSelect) return;
    
    const varmegyek = [
        'Budapest',
        'Bács-Kiskun',
        'Baranya',
        'Békés',
        'Borsod-Abaúj-Zemplén',
        'Csongrád-Csanád',
        'Fejér',
        'Győr-Moson-Sopron',
        'Hajdú-Bihar',
        'Heves',
        'Jász-Nagykun-Szolnok',
        'Komárom-Esztergom',
        'Nógrád',
        'Pest',
        'Somogy',
        'Szabolcs-Szatmár-Bereg',
        'Tolna',
        'Vas',
        'Veszprém',
        'Zala'
    ];
    
    const currentValue = varmegyeSelect.value;
    
    if (currentValue && !varmegyek.includes(currentValue) && currentValue !== "no_match") {
        const existingNoMatch = varmegyeSelect.querySelector('option[value="no_match"]');
        if (existingNoMatch) {
            existingNoMatch.remove();
        }
        
        const noMatchOption = document.createElement('option');
        noMatchOption.value = "no_match";
        noMatchOption.textContent = `Nincs ilyen: ${currentValue}`;
        noMatchOption.style.color = '#ff4444';
        noMatchOption.style.fontStyle = 'italic';
        varmegyeSelect.appendChild(noMatchOption);
        
        varmegyeSelect.value = "no_match";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    if (!form) return;
    
    const profilePic = document.querySelector('.profile_pic');
    const editIcon = document.querySelector('.edit_icon');
    const fileInput = document.getElementById('profilkep');
    
    if (editIcon) {
        editIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            fileInput.click();
        });
    }
    
    if (profilePic) {
        profilePic.addEventListener('click', function(e) {
            if (!e.target.closest('.edit_icon')) {
                fileInput.click();
            }
        });
    }
    
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            previewImage(this);
            checkFormChanges();
        });
    }
    
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (input.type !== 'file') {
            input.defaultValue = input.value;
        }
    });
    
    inputs.forEach(input => {
        input.addEventListener('input', checkFormChanges);
        input.addEventListener('change', checkFormChanges);
    });
    
    form.addEventListener('reset', function() {
        setTimeout(() => {
            setFormModified(false, 0);
            inputs.forEach(input => {
                if (input.type !== 'file') {
                    input.defaultValue = input.value;
                }
            });
        }, 0);
    });
    
    form.addEventListener('submit', function() {
        setFormModified(false, 0);
    });
    
    window.addEventListener('beforeunload', handleBeforeUnload);
    
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link && link.href && !link.href.includes('javascript:')) {
            handleLinkClick(e);
        }
    });
    
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
    
    initCountyValidation();
    
    const varmegyeSelect = document.getElementById('varmegye');
    if (varmegyeSelect) {
        varmegyeSelect.addEventListener('change', function() {
            if (this.value === "no_match") {
                alert('Kérjük, válasszon a listából egy érvényes vármegyét!');
                this.value = "";
                
                const noMatchOption = this.querySelector('option[value="no_match"]');
                if (noMatchOption) {
                    noMatchOption.remove();
                }
            }
            checkFormChanges();
        });
    }
});