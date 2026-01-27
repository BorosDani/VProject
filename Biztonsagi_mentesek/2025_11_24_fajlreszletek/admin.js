// assets/js/admin.js - WebAdmin JavaScript funkciók - Javított verzió

class WebAdmin {
    constructor() {
        this.currentTab = this.getCurrentTab();
        this.init();
    }

    init() {
        this.initEventListeners();
        this.initSorting();
        this.initModals();
        this.initUserMenu();
        this.initThemeToggle();
        this.showCurrentTab();
    }

    getCurrentTab() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('tab') || 'dashboard';
    }

    initEventListeners() {
        // Tab navigation - AJAX betöltéssel
        document.querySelectorAll('.admin-nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const tab = e.target.getAttribute('data-tab');
                this.switchTab(tab);
            });
        });

        // Search forms
        document.querySelectorAll('.admin-search-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitSearch(form);
            });
        });

        // Enter key in search
        document.querySelectorAll('.admin-search-form input[type="text"]').forEach(input => {
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.submitSearch(input.closest('form'));
                }
            });
        });
    }

    initSorting() {
        document.querySelectorAll('.admin-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                this.sortTable(th);
            });
        });
    }

    initModals() {
        // Close modals on background click
        document.querySelectorAll('.admin-modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeModal(modal);
                }
            });
        });

        // Close modals on X button click
        document.querySelectorAll('.admin-modal-close').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const modal = e.target.closest('.admin-modal');
                this.closeModal(modal);
            });
        });

        // ESC key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.admin-modal.show').forEach(modal => {
                    this.closeModal(modal);
                });
            }
        });
    }

    initUserMenu() {
        const userInfo = document.querySelector('.admin-user-info');
        const dropdown = document.querySelector('.admin-user-dropdown');

        if (userInfo && dropdown) {
            userInfo.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!userInfo.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });
        }
    }

    initThemeToggle() {
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            // Betöltjük a mentett témát
            const savedTheme = localStorage.getItem('admin-theme') || 'light';
            this.setTheme(savedTheme);
            themeToggle.checked = savedTheme === 'dark';

            themeToggle.addEventListener('change', (e) => {
                const theme = e.target.checked ? 'dark' : 'light';
                this.setTheme(theme);
                localStorage.setItem('admin-theme', theme);
                
                // Ne töltse újra az oldalt!
                e.preventDefault();
                return false;
            });
        }
    }

    setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
    }

    showCurrentTab() {
        // Minden tab tartalmat elrejtjük
        document.querySelectorAll('.admin-tab-content').forEach(content => {
            content.classList.remove('active');
        });

        // Aktuális tab tartalmát megjelenítjük
        const currentContent = document.getElementById(`${this.currentTab}-content`);
        if (currentContent) {
            currentContent.classList.add('active');
        }

        // Navigáció aktív állapota
        document.querySelectorAll('.admin-nav-link').forEach(link => {
            link.classList.remove('active');
        });
        const currentLink = document.querySelector(`.admin-nav-link[data-tab="${this.currentTab}"]`);
        if (currentLink) {
            currentLink.classList.add('active');
        }
    }

    switchTab(tab) {
        // URL frissítése
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        url.searchParams.delete('page');
        window.history.pushState({}, '', url);

        // Aktuális tab frissítése
        this.currentTab = tab;
        this.showCurrentTab();

        // Ne töltsd újra az oldalt - csak a tartalmat váltjuk
        // window.location.reload(); // EZT TÁVOLÍTOTTUK EL!
    }

    submitSearch(form) {
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        
        // URL frissítése és oldal újratöltése
        const url = new URL(window.location);
        url.search = params.toString();
        window.location.href = url.toString();
    }

    sortTable(th) {
        const column = th.getAttribute('data-column');
        const currentOrder = th.classList.contains('sort-asc') ? 'ASC' : 
                           th.classList.contains('sort-desc') ? 'DESC' : 'DESC';
        
        const newOrder = currentOrder === 'ASC' ? 'DESC' : 'ASC';

        // URL frissítése
        const url = new URL(window.location);
        url.searchParams.set('order_by', column);
        url.searchParams.set('order_dir', newOrder);
        window.location.href = url.toString();
    }

    showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    closeModal(modal) {
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    showStatusModal(userId, currentStatus, userName) {
        document.getElementById('status_user_id').value = userId;
        document.getElementById('status_user_name').textContent = userName;
        document.querySelector('#statusForm select[name="status"]').value = currentStatus;
        this.showModal('statusModal');
    }

    async showEditModal(userId) {
        try {
            // Felhasználó adatainak betöltése AJAX-al
            const response = await fetch(`?action=get_user_data&user_id=${userId}`);
            const data = await response.json();
            
            if (data.success) {
                const user = data.user;
                
                // Mezők kitöltése
                document.getElementById('edit_user_id').value = userId;
                document.getElementById('edit_fnev').value = user.fnev || '';
                document.getElementById('edit_email').value = user.email || '';
                document.getElementById('edit_knev').value = user.knev || '';
                document.getElementById('edit_vnev').value = user.vnev || '';
                document.getElementById('edit_nem').value = user.nem || 'nem_publikus';
                document.getElementById('edit_szuletett').value = user.szuletett || '';
                document.getElementById('edit_telefon').value = user.telefon || '';
                document.getElementById('edit_varos').value = user.varos || '';
                document.getElementById('edit_reszletek').value = user.reszletek || '';
                
                this.showModal('editModal');
            } else {
                this.showAlert('Hiba történt a felhasználó adatainak betöltése során!', 'error');
            }
        } catch (error) {
            console.error('Error loading user data:', error);
            this.showAlert('Hiba történt a felhasználó adatainak betöltése során!', 'error');
        }
    }

    showRoleModal(userId, currentRole, userName) {
        document.getElementById('role_user_id').value = userId;
        document.getElementById('role_user_name').textContent = userName;
        
        const adminBtn = document.getElementById('makeAdminBtn');
        const userBtn = document.getElementById('makeUserBtn');
        
        if (currentRole === 'admin') {
            adminBtn.style.display = 'none';
            userBtn.style.display = 'block';
        } else {
            adminBtn.style.display = 'block';
            userBtn.style.display = 'none';
        }
        
        this.showModal('roleModal');
    }

    deleteUser(userId, userName) {
        if (confirm(`Biztosan törölni szeretnéd a(z) "${userName}" felhasználót?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="delete_user">
                <input type="hidden" name="user_id" value="${userId}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    deleteActivity(activityId, activityName, userName = '') {
        let message = `Biztosan törölni szeretnéd a(z) "${activityName}" tevékenységet?`;
        
        if (userName) {
            message = `Biztosan törölni szeretnéd a(z) "${userName}" felhasználó "${activityName}" tevékenységét?`;
        }
        
        if (confirm(message)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="delete_activity">
                <input type="hidden" name="activity_id" value="${activityId}">
                <input type="hidden" name="table" value="${this.getCurrentTable()}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    getCurrentTable() {
        const tableMap = {
            'user-activities': 'felhasznalo_tevekenyseg',
            'admin-activities': 'admin_tevekenyseg',
            'email-verifications': 'email_ell',
            'password-resets': 'jelszo_visszaallitasok',
            'deleted-users': 'torolt_felhasznalok'
        };
        return tableMap[this.currentTab] || 'felhasznalo_tevekenyseg';
    }

    showAlert(message, type = 'info') {
        const alert = document.createElement('div');
        alert.className = `admin-alert admin-alert-${type}`;
        alert.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>${message}</span>
                <button type="button" onclick="this.parentElement.parentElement.remove()" 
                        style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: inherit;">×</button>
            </div>
        `;
        
        const container = document.querySelector('.admin-container');
        const nav = document.querySelector('.admin-nav');
        container.insertBefore(alert, nav);
        
        setTimeout(() => {
            if (alert.parentElement) {
                alert.remove();
            }
        }, 5000);
    }

    // Utility functions
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('hu-HU');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.admin = new WebAdmin();
});

// Global functions for HTML onclick attributes
function showStatusModal(userId, currentStatus, userName) {
    if (window.admin) {
        window.admin.showStatusModal(userId, currentStatus, userName);
    }
}

function showEditModal(userId) {
    if (window.admin) {
        window.admin.showEditModal(userId);
    }
}

function showRoleModal(userId, currentRole, userName) {
    if (window.admin) {
        window.admin.showRoleModal(userId, currentRole, userName);
    }
}

function deleteUser(userId, userName) {
    if (window.admin) {
        window.admin.deleteUser(userId, userName);
    }
}

function deleteActivity(activityId, activityName, userName = '') {
    if (window.admin) {
        window.admin.deleteActivity(activityId, activityName, userName);
    }
}

// Browser navigation (back/forward) handling
window.addEventListener('popstate', () => {
    if (window.admin) {
        window.admin.currentTab = window.admin.getCurrentTab();
        window.admin.showCurrentTab();
    }
});