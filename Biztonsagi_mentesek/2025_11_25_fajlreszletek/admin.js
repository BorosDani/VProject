// assets/js/admin.js - WebAdmin JavaScript funkciók - Sidebar menüvel

class WebAdmin {
    constructor() {
        this.currentTab = this.getCurrentTab();
        this.isRefreshing = false;
        this.refreshInterval = null;
        this.init();
    }

    init() {
        this.initEventListeners();
        this.initSorting();
        this.initModals();
        this.initUserMenu();
        this.initThemeToggle();
        this.showCurrentTab();
        this.startAutoRefresh();
    }

    getCurrentTab() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('tab') || 'dashboard';
    }

    // Automatikus frissítés indítása
    startAutoRefresh() {
        // Frissítés minden 30 másodpercben
        this.refreshInterval = setInterval(() => {
            this.refreshCurrentTab();
        }, 30000);
    }

    // Jelenlegi tab adatainak frissítése
    async refreshCurrentTab() {
        if (this.isRefreshing) return;
        
        this.isRefreshing = true;
        
        try {
            const urlParams = new URLSearchParams(window.location.search);
            const params = {
                action: 'refresh_data',
                tab: this.currentTab,
                page: urlParams.get('page') || 1,
                search: urlParams.get('search') || '',
                status: urlParams.get('status') || '',
                category: urlParams.get('category') || '',
                order_by: urlParams.get('order_by') || '',
                order_dir: urlParams.get('order_dir') || 'DESC'
            };

            const queryString = new URLSearchParams(params).toString();
            const response = await fetch(`?${queryString}`);
            const data = await response.json();

            if (data.success) {
                this.updateTabContent(data.data || data);
            }
        } catch (error) {
            console.error('Refresh error:', error);
        } finally {
            this.isRefreshing = false;
        }
    }

    // Tab tartalmának frissítése
    updateTabContent(data) {
        switch (this.currentTab) {
            case 'users':
                this.updateUsersTable(data);
                break;
            case 'admin-activities':
                this.updateAdminActivitiesTable(data);
                break;
            case 'user-activities':
                this.updateUserActivitiesTable(data);
                break;
            case 'email-verifications':
                this.updateEmailVerificationsTable(data);
                break;
            case 'password-resets':
                this.updatePasswordResetsTable(data);
                break;
            case 'deleted-users':
                this.updateDeletedUsersTable(data);
                break;
            case 'dashboard':
                this.updateDashboard(data);
                break;
        }

        // Frissítjük a lapozást is
        if (data.pages) {
            this.updatePagination(data);
        }
    }

    // Dashboard frissítése
    updateDashboard(data) {
        if (data.stats) {
            this.updateStats(data.stats);
        }
        if (data.recent_activities) {
            this.updateRecentActivities(data.recent_activities);
        }
    }

    // Statisztikák frissítése
    updateStats(stats) {
        const statsContainer = document.getElementById('stats-container');
        if (!statsContainer) return;

        let html = `
            <div class="admin-stat-card">
                <div class="admin-stat-label">Összes Felhasználó</div>
                <div class="admin-stat-value">${stats.users.total}</div>
                <div class="admin-stat-details">
                    Aktív: ${stats.users.active} | 
                    Függőben: ${stats.users.pending}
                </div>
            </div>
            
            <div class="admin-stat-card">
                <div class="admin-stat-label">Kitiltott Felhasználók</div>
                <div class="admin-stat-value">${stats.users.banned}</div>
                <div class="admin-stat-details">
                    Összes kitiltott felhasználó
                </div>
            </div>
            
            <div class="admin-stat-card">
                <div class="admin-stat-label">Admin Tevékenységek</div>
                <div class="admin-stat-value">${stats.admin_activities.total}</div>
                <div class="admin-stat-details">
                    Ma: ${stats.admin_activities.today}
                </div>
            </div>
            
            <div class="admin-stat-card">
                <div class="admin-stat-label">Felhasználói Tevékenységek</div>
                <div class="admin-stat-value">${stats.user_activities.total}</div>
                <div class="admin-stat-details">
                    Ma: ${stats.user_activities.today} | 
                    Sikertelen: ${stats.user_activities.failed}
                </div>
            </div>
            
            <div class="admin-stat-card">
                <div class="admin-stat-label">Email Ellenőrzések</div>
                <div class="admin-stat-value">${stats.email_verifications.total}</div>
                <div class="admin-stat-details">
                    Megerősítve: ${stats.email_verifications.verified} | 
                    Függőben: ${stats.email_verifications.pending}
                </div>
            </div>
            
            <div class="admin-stat-card">
                <div class="admin-stat-label">Törölt Felhasználók</div>
                <div class="admin-stat-value">${stats.deleted_users}</div>
                <div class="admin-stat-details">
                    Összes törölt felhasználó
                </div>
            </div>
        `;

        statsContainer.innerHTML = html;
    }

    // Legutóbbi tevékenységek frissítése
    updateRecentActivities(activities) {
        const container = document.getElementById('recent-activities-container');
        if (!container) return;

        let html = '';
        if (activities && activities.length > 0) {
            html = `
                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Felhasználó</th>
                                <th>Tevékenység</th>
                                <th>Kategória</th>
                                <th>Időpont</th>
                                <th>Sikeresség</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            activities.forEach(activity => {
                html += `
                    <tr>
                        <td>${activity.ftid}</td>
                        <td>
                            ${activity.fnev ? 
                                `${this.escapeHtml(activity.fnev)}<br><small>${this.escapeHtml(activity.email)}</small>` : 
                                '<em>Ismeretlen</em>'}
                        </td>
                        <td>${this.escapeHtml(activity.tevekenyseg)}</td>
                        <td>
                            <span class="admin-badge admin-badge-secondary">
                                ${this.formatActivityCategory(activity.kategoria)}
                            </span>
                        </td>
                        <td>${this.formatDate(activity.idopont)}</td>
                        <td>
                            ${activity.sikeresseg === 'sikeres' ? 
                                '<span class="admin-badge admin-badge-success">Sikeres</span>' : 
                                '<span class="admin-badge admin-badge-danger">Sikertelen</span>'}
                        </td>
                    </tr>
                `;
            });
            
            html += `
                        </tbody>
                    </table>
                </div>
            `;
        } else {
            html = '<p>Nincsenek tevékenységek.</p>';
        }
        
        container.innerHTML = html;
    }

    // Felhasználók táblázat frissítése
    updateUsersTable(data) {
        const tbody = document.getElementById('users-table-body');
        if (!tbody) return;

        let html = '';
        if (data.users && data.users.length > 0) {
            data.users.forEach(user => {
                html += `
                    <tr>
                        <td>${user.fid}</td>
                        <td><strong>${this.escapeHtml(user.fnev)}</strong></td>
                        <td>${this.escapeHtml(user.email)}</td>
                        <td>
                            ${user.vnev || user.knev ? 
                                `${this.escapeHtml(user.vnev || '')} ${this.escapeHtml(user.knev || '')}` : 
                                '<em>Nincs megadva</em>'}
                        </td>
                        <td>
                            <span class="admin-badge ${user.szerep === 'admin' ? 'admin-badge-warning' : 'admin-badge-secondary'}">
                                ${user.szerep}
                            </span>
                        </td>
                        <td>${this.formatStatus(user.statusz)}</td>
                        <td>
                            ${user.email_megerositve ? 
                                '<span class="admin-badge admin-badge-success">Igen</span>' : 
                                '<span class="admin-badge admin-badge-warning">Nem</span>'}
                        </td>
                        <td>${this.escapeHtml(user.statusz_ok || '-')}</td>
                        <td>${user.statusz_meddig ? this.formatDate(user.statusz_meddig) : '-'}</td>
                        <td>${this.formatDate(user.regisztralt)}</td>
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                <button class="admin-btn admin-btn-sm admin-btn-primary" 
                                        onclick="showEditModal(${user.fid})">
                                    Szerkesztés
                                </button>
                                <button class="admin-btn admin-btn-sm admin-btn-info" 
                                        onclick="showRoleModal(${user.fid}, '${user.szerep}', '${this.escapeHtml(user.fnev)}')">
                                    Szerep
                                </button>
                                <button class="admin-btn admin-btn-sm admin-btn-warning" 
                                        onclick="showStatusModal(${user.fid}, '${user.statusz}', '${this.escapeHtml(user.fnev)}')">
                                    Státusz
                                </button>
                                ${user.szerep !== 'admin' ? `
                                <button class="admin-btn admin-btn-sm admin-btn-danger" 
                                        onclick="deleteUser(${user.fid}, '${this.escapeHtml(user.fnev)}')">
                                    Törlés
                                </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="11" style="text-align: center; padding: 2rem;">Nincsenek felhasználók.</td></tr>';
        }
        
        tbody.innerHTML = html;
    }

    // Admin tevékenységek táblázat frissítése
    updateAdminActivitiesTable(data) {
        const tbody = document.getElementById('admin-activities-table-body');
        if (!tbody) return;

        let html = '';
        if (data.activities && data.activities.length > 0) {
            data.activities.forEach(activity => {
                html += `
                    <tr>
                        <td>${activity.atid}</td>
                        <td>
                            ${activity.admin_felhasznalo ? 
                                this.escapeHtml(activity.admin_felhasznalo) : 
                                '<em>Ismeretlen</em>'}
                        </td>
                        <td>
                            ${activity.cel_felhasznalo ? 
                                this.escapeHtml(activity.cel_felhasznalo) : 
                                '<em>-</em>'}
                        </td>
                        <td>${this.escapeHtml(activity.tevekenyseg)}</td>
                        <td>${this.escapeHtml(activity.reszletek || '-')}</td>
                        <td>${this.formatDate(activity.idopont)}</td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="6" style="text-align: center; padding: 2rem;">Nincsenek admin tevékenységek.</td></tr>';
        }
        
        tbody.innerHTML = html;
    }

    // Felhasználói tevékenységek táblázat frissítése
    updateUserActivitiesTable(data) {
        const tbody = document.getElementById('user-activities-table-body');
        if (!tbody) return;

        let html = '';
        if (data.activities && data.activities.length > 0) {
            data.activities.forEach(activity => {
                html += `
                    <tr>
                        <td>${activity.ftid}</td>
                        <td>
                            ${activity.fnev ? 
                                `${this.escapeHtml(activity.fnev)}<br><small>${this.escapeHtml(activity.email)}</small>` : 
                                '<em>Ismeretlen</em>'}
                        </td>
                        <td>${this.escapeHtml(activity.tevekenyseg)}</td>
                        <td>
                            <span class="admin-badge admin-badge-secondary">
                                ${this.formatActivityCategory(activity.kategoria)}
                            </span>
                        </td>
                        <td>${this.formatDate(activity.idopont)}</td>
                        <td>
                            ${activity.sikeresseg === 'sikeres' ? 
                                '<span class="admin-badge admin-badge-success">Sikeres</span>' : 
                                '<span class="admin-badge admin-badge-danger">Sikertelen</span>'}
                        </td>
                        <td>
                            <button class="admin-btn admin-btn-sm admin-btn-danger" 
                                    onclick="deleteActivity(${activity.ftid}, '${this.escapeHtml(activity.tevekenyseg)}', '${this.escapeHtml(activity.fnev)}')">
                                Törlés
                            </button>
                        </td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="7" style="text-align: center; padding: 2rem;">Nincsenek felhasználói tevékenységek.</td></tr>';
        }
        
        tbody.innerHTML = html;
    }

    // Email ellenőrzések táblázat frissítése
    updateEmailVerificationsTable(data) {
        const tbody = document.getElementById('email-verifications-table-body');
        if (!tbody) return;

        let html = '';
        if (data.verifications && data.verifications.length > 0) {
            data.verifications.forEach(verification => {
                html += `
                    <tr>
                        <td>${verification.emid}</td>
                        <td>${this.escapeHtml(verification.fnev)}</td>
                        <td>${this.escapeHtml(verification.email)}</td>
                        <td>${this.formatEmailStatus(verification)}</td>
                        <td>${this.formatDate(verification.lejarati_ido)}</td>
                        <td>${this.formatDate(verification.idopont)}</td>
                        <td>
                            <button class="admin-btn admin-btn-sm admin-btn-danger" 
                                    onclick="deleteActivity(${verification.emid}, 'Email ellenőrzés', '${this.escapeHtml(verification.fnev)}')">
                                Törlés
                            </button>
                        </td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="7" style="text-align: center; padding: 2rem;">Nincsenek email ellenőrzések.</td></tr>';
        }
        
        tbody.innerHTML = html;
    }

    // Jelszó visszaállítások táblázat frissítése
    updatePasswordResetsTable(data) {
        const tbody = document.getElementById('password-resets-table-body');
        if (!tbody) return;

        let html = '';
        if (data.resets && data.resets.length > 0) {
            data.resets.forEach(reset => {
                html += `
                    <tr>
                        <td>${reset.jvid}</td>
                        <td>${this.escapeHtml(reset.fnev)}</td>
                        <td>${this.escapeHtml(reset.email)}</td>
                        <td>${this.formatResetStatus(reset)}</td>
                        <td>${this.formatDate(reset.lejarati_ido)}</td>
                        <td>${this.formatDate(reset.idopont)}</td>
                        <td>
                            <button class="admin-btn admin-btn-sm admin-btn-danger" 
                                    onclick="deleteActivity(${reset.jvid}, 'Jelszó visszaállítás', '${this.escapeHtml(reset.fnev)}')">
                                Törlés
                            </button>
                        </td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="7" style="text-align: center; padding: 2rem;">Nincsenek jelszó visszaállítások.</td></tr>';
        }
        
        tbody.innerHTML = html;
    }

    // Törölt felhasználók táblázat frissítése
    updateDeletedUsersTable(data) {
        const tbody = document.getElementById('deleted-users-table-body');
        if (!tbody) return;

        let html = '';
        if (data.users && data.users.length > 0) {
            data.users.forEach(user => {
                html += `
                    <tr>
                        <td>${user.fid}</td>
                        <td>${this.escapeHtml(user.fnev)}</td>
                        <td>${this.escapeHtml(user.email)}</td>
                        <td>
                            ${user.vnev || user.knev ? 
                                `${this.escapeHtml(user.vnev || '')} ${this.escapeHtml(user.knev || '')}` : 
                                '<em>Nincs megadva</em>'}
                        </td>
                        <td>
                            <span class="admin-badge ${user.szerep === 'admin' ? 'admin-badge-warning' : 'admin-badge-secondary'}">
                                ${user.szerep}
                            </span>
                        </td>
                        <td>${this.formatDate(user.regisztralt)}</td>
                        <td>${this.formatDate(user.torles_idopontja)}</td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="7" style="text-align: center; padding: 2rem;">Nincsenek törölt felhasználók.</td></tr>';
        }
        
        tbody.innerHTML = html;
    }

    // Státusz formázása
    formatStatus(status) {
        const statuses = {
            'Aktiv': ['Aktív', 'admin-badge-success'],
            'Fuggoben': ['Függőben', 'admin-badge-warning'],
            'Kitiltott': ['Kitiltott', 'admin-badge-danger']
        };
        
        if (statuses[status]) {
            return `<span class="admin-badge ${statuses[status][1]}">${statuses[status][0]}</span>`;
        }
        
        return `<span class="admin-badge admin-badge-secondary">${status}</span>`;
    }

    // Email státusz formázása - JAVÍTOTT
    formatEmailStatus(verification) {
        if (verification.ellenorzve) {
            return '<span class="admin-badge admin-badge-success">Megerősítve</span>';
        }
        
        // Ellenőrizzük a lejárati dátumot
        if (!verification.lejarati_ido || 
            verification.lejarati_ido === '0000-00-00 00:00:00') {
            return '<span class="admin-badge admin-badge-warning">Függőben</span>';
        }
        
        try {
            const lejaratiDate = new Date(verification.lejarati_ido);
            if (isNaN(lejaratiDate.getTime())) {
                return '<span class="admin-badge admin-badge-warning">Függőben</span>';
            }
            
            if (lejaratiDate < new Date()) {
                return '<span class="admin-badge admin-badge-danger">Lejárt</span>';
            } else {
                return '<span class="admin-badge admin-badge-warning">Függőben</span>';
            }
        } catch (error) {
            return '<span class="admin-badge admin-badge-warning">Függőben</span>';
        }
    }

    // Reset státusz formázása - JAVÍTOTT
    formatResetStatus(reset) {
        if (reset.felhasznalva) {
            return '<span class="admin-badge admin-badge-success">Felhasználva</span>';
        }
        
        // Ellenőrizzük a lejárati dátumot
        if (!reset.lejarati_ido || 
            reset.lejarati_ido === '0000-00-00 00:00:00') {
            return '<span class="admin-badge admin-badge-warning">Aktív</span>';
        }
        
        try {
            const lejaratiDate = new Date(reset.lejarati_ido);
            if (isNaN(lejaratiDate.getTime())) {
                return '<span class="admin-badge admin-badge-warning">Aktív</span>';
            }
            
            if (lejaratiDate < new Date()) {
                return '<span class="admin-badge admin-badge-danger">Lejárt</span>';
            } else {
                return '<span class="admin-badge admin-badge-warning">Aktív</span>';
            }
        } catch (error) {
            return '<span class="admin-badge admin-badge-warning">Aktív</span>';
        }
    }

    // Tevékenység kategória formázása
    formatActivityCategory(category) {
        const categories = {
            'bejelentkezes': 'Bejelentkezés',
            'profil': 'Profil',
            'biztonsag': 'Biztonság',
            'egyeb': 'Egyéb'
        };
        
        return categories[category] || category;
    }

    // Dátum formázása - JAVÍTOTT VERZIÓ
    formatDate(dateString) {
        if (!dateString || dateString === '0000-00-00 00:00:00' || dateString === '0000-00-00') {
            return '-';
        }
        
        try {
            const date = new Date(dateString);
            // Ellenőrizzük, hogy érvényes-e a dátum
            if (isNaN(date.getTime())) {
                return '-';
            }
            return date.toLocaleString('hu-HU');
        } catch (error) {
            console.error('Date formatting error:', error);
            return '-';
        }
    }

    // HTML escape
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Lapozás frissítése
    updatePagination(data) {
        const paginationElement = document.querySelector(`#${this.currentTab}-content .admin-pagination`);
        if (!paginationElement || data.pages <= 1) return;

        const baseUrl = this.getBaseUrl();
        paginationElement.innerHTML = this.generatePagination(data.current_page, data.pages, baseUrl);
    }

    getBaseUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        const params = {
            url: 'webadmin',
            tab: this.currentTab,
            search: urlParams.get('search') || '',
            status: urlParams.get('status') || '',
            category: urlParams.get('category') || '',
            order_by: urlParams.get('order_by') || '',
            order_dir: urlParams.get('order_dir') || 'DESC'
        };

        return `webadmin?${new URLSearchParams(params).toString()}`;
    }

    generatePagination(currentPage, totalPages, baseUrl) {
        if (totalPages <= 1) return '';
        
        let pagination = '';
        const maxPagesToShow = 10;
        const half = Math.floor(maxPagesToShow / 2);
        
        let startPage = Math.max(1, currentPage - half);
        let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);
        
        if (endPage - startPage + 1 < maxPagesToShow) {
            startPage = Math.max(1, endPage - maxPagesToShow + 1);
        }
        
        // Előző oldal
        if (currentPage > 1) {
            const prevUrl = `${baseUrl}&page=${currentPage - 1}`;
            pagination += `<li><a href="${prevUrl}">&laquo;</a></li>`;
        }
        
        // Első oldal
        if (startPage > 1) {
            pagination += `<li><a href="${baseUrl}&page=1">1</a></li>`;
            if (startPage > 2) {
                pagination += '<li><span class="pagination-ellipsis">...</span></li>';
            }
        }
        
        // Oldalak
        for (let i = startPage; i <= endPage; i++) {
            const active = i == currentPage ? 'active' : '';
            const pageUrl = `${baseUrl}&page=${i}`;
            pagination += `<li><a href="${pageUrl}" class="${active}">${i}</a></li>`;
        }
        
        // Utolsó oldal
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                pagination += '<li><span class="pagination-ellipsis">...</span></li>';
            }
            pagination += `<li><a href="${baseUrl}&page=${totalPages}">${totalPages}</a></li>`;
        }
        
        // Következő oldal
        if (currentPage < totalPages) {
            const nextUrl = `${baseUrl}&page=${currentPage + 1}`;
            pagination += `<li><a href="${nextUrl}">&raquo;</a></li>`;
        }
        
        return pagination;
    }

    initEventListeners() {
        // Sidebar navigation
        document.querySelectorAll('.admin-nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const tab = e.currentTarget.getAttribute('data-tab');
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
                
                // Chevron ikon forgatása
                const chevron = userInfo.querySelector('.fa-chevron-down');
                if (chevron) {
                    chevron.style.transform = dropdown.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!userInfo.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                    
                    // Chevron ikon visszaállítása
                    const chevron = userInfo.querySelector('.fa-chevron-down');
                    if (chevron) {
                        chevron.style.transform = 'rotate(0deg)';
                    }
                }
            });

            // Close dropdown when pressing Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                    
                    // Chevron ikon visszaállítása
                    const chevron = userInfo.querySelector('.fa-chevron-down');
                    if (chevron) {
                        chevron.style.transform = 'rotate(0deg)';
                    }
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
        
        // Azonnali adatfrissítés
        this.refreshCurrentTab();
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

        // URL paraméterek összeállítása - JAVÍTOTT VERZIÓ
        const url = new URL(window.location);
        url.searchParams.set('order_by', column);
        url.searchParams.set('order_dir', newOrder);
        
        // Eltávolítjuk a page paramétert, hogy az 1. oldalra kerüljünk
        url.searchParams.delete('page');
        
        window.location.href = url.toString();
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

    // Felhasználó adatainak betöltése szerkesztéshez - JAVÍTOTT VERZIÓ
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
                
                // Dátum formázása (YYYY-MM-DD)
                if (user.szuletett) {
                    const birthDate = new Date(user.szuletett);
                    document.getElementById('edit_szuletett').value = birthDate.toISOString().split('T')[0];
                } else {
                    document.getElementById('edit_szuletett').value = '';
                }
                
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
            form.action = '';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'delete_user';
            
            const userIdInput = document.createElement('input');
            userIdInput.type = 'hidden';
            userIdInput.name = 'user_id';
            userIdInput.value = userId;
            
            form.appendChild(actionInput);
            form.appendChild(userIdInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Tevékenység törlése - JAVÍTOTT VERZIÓ
    deleteActivity(activityId, activityName, userName = '') {
        let message = `Biztosan törölni szeretnéd a(z) "${activityName}" tevékenységet?`;
        
        if (userName) {
            message = `Biztosan törölni szeretnéd a(z) "${userName}" felhasználó "${activityName}" tevékenységét?`;
        }
        
        if (confirm(message)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'delete_activity';
            
            const activityInput = document.createElement('input');
            activityInput.type = 'hidden';
            activityInput.name = 'activity_id';
            activityInput.value = activityId;
            
            const tableInput = document.createElement('input');
            tableInput.type = 'hidden';
            tableInput.name = 'table';
            tableInput.value = this.getCurrentTable();
            
            form.appendChild(actionInput);
            form.appendChild(activityInput);
            form.appendChild(tableInput);
            
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
        
        const container = document.querySelector('.admin-main');
        const header = document.querySelector('.admin-header');
        container.insertBefore(alert, header.nextSibling);
        
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

// Browser navigation handling
window.addEventListener('popstate', () => {
    if (window.admin) {
        window.admin.currentTab = window.admin.getCurrentTab();
        window.admin.showCurrentTab();
        window.admin.refreshCurrentTab();
    }
});