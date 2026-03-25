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

    startAutoRefresh() {
        this.refreshInterval = setInterval(() => {
            this.refreshCurrentTab();
        }, 30000);
    }

    async refreshCurrentTab(pageOverride = null) {
        if (this.isRefreshing) return;
        this.isRefreshing = true;
        try {
            const urlParams = new URLSearchParams(window.location.search);
            const params = {
                action: 'refresh_data',
                tab: this.currentTab,
                page: pageOverride || urlParams.get('page') || 1,
                search: urlParams.get('search') || '',
                status: urlParams.get('status') || '',
                category: urlParams.get('category') || '',
                order_by: urlParams.get('order_by') || '',
                order_dir: urlParams.get('order_dir') || 'DESC'
            };
            if (pageOverride) {
                urlParams.set('page', pageOverride);
                window.history.pushState({}, '', `${window.location.pathname}?${urlParams.toString()}`);
            }
            const queryString = new URLSearchParams(params).toString();
            const response = await fetch(`?${queryString}`);
            const data = await response.json();
            if (data.success) {
                this.updateTabContent(data.data || data);
            }
        } catch (error) {
            console.error(error);
        } finally {
            this.isRefreshing = false;
        }
    }

    updateTabContent(data) {
        switch (this.currentTab) {
            case 'users': this.updateUsersTable(data); break;
            case 'admin-activities': this.updateAdminActivitiesTable(data); break;
            case 'user-activities': this.updateUserActivitiesTable(data); break;
            case 'email-verifications': this.updateEmailVerificationsTable(data); break;
            case 'password-resets': this.updatePasswordResetsTable(data); break;
            case 'deleted-users': this.updateDeletedUsersTable(data); break;
            case 'comments': this.updateCommentsTable(data); break;
            case 'deleted-comments': this.updateDeletedCommentsTable(data); break;
            case 'dashboard': this.updateDashboard(data); break;
        }
        if (data.pages !== undefined) {
            this.updatePagination(data);
        }
    }

    updateDashboard(data) {
        if (data.stats) this.updateStats(data.stats);
        if (data.recent_activities) this.updateRecentActivities(data.recent_activities);
    }

    updateStats(stats) {
        const statsContainer = document.getElementById('stats-container');
        if (!statsContainer) return;
        statsContainer.innerHTML = `
            <div class="admin-stat-card">
                <div class="admin-stat-label">Összes Felhasználó</div>
                <div class="admin-stat-value">${stats.users.total}</div>
                <div class="admin-stat-details">Aktív: ${stats.users.active} | Függőben: ${stats.users.pending}</div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-label">Kitiltott Felhasználók</div>
                <div class="admin-stat-value">${stats.users.banned}</div>
                <div class="admin-stat-details">Összes kitiltott felhasználó</div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-label">Admin Tevékenységek</div>
                <div class="admin-stat-value">${stats.admin_activities.total}</div>
                <div class="admin-stat-details">Ma: ${stats.admin_activities.today}</div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-label">Felhasználói Tevékenységek</div>
                <div class="admin-stat-value">${stats.user_activities.total}</div>
                <div class="admin-stat-details">Ma: ${stats.user_activities.today} | Sikertelen: ${stats.user_activities.failed}</div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-label">Email Ellenőrzések</div>
                <div class="admin-stat-value">${stats.email_verifications.total}</div>
                <div class="admin-stat-details">Megerősítve: ${stats.email_verifications.verified} | Függőben: ${stats.email_verifications.pending}</div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-label">Törölt Felhasználók</div>
                <div class="admin-stat-value">${stats.deleted_users}</div>
                <div class="admin-stat-details">Összes törölt felhasználó</div>
            </div>
        `;
    }

    updateRecentActivities(activities) {
        const container = document.getElementById('recent-activities-container');
        if (!container) return;
        if (activities && activities.length > 0) {
            let rows = activities.map(activity => `
                <tr>
                    <td>${activity.fnev ? `${this.escapeHtml(activity.fnev)}<br><small>${this.escapeHtml(activity.email)}</small>` : '<em>Ismeretlen</em>'}</td>
                    <td>${this.escapeHtml(activity.tevekenyseg)}</td>
                    <td><span class="admin-badge admin-badge-secondary">${this.formatActivityCategory(activity.kategoria)}</span></td>
                    <td>${this.formatDate(activity.idopont)}</td>
                    <td>${activity.sikeresseg === 'sikeres' ? '<span class="admin-badge admin-badge-success">Sikeres</span>' : '<span class="admin-badge admin-badge-danger">Sikertelen</span>'}</td>
                </tr>
            `).join('');
            container.innerHTML = `
                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead><tr><th>Felhasználó</th><th>Tevékenység</th><th>Kategória</th><th>Időpont</th><th>Sikeresség</th></tr></thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>`;
        } else {
            container.innerHTML = '<p>Nincsenek tevékenységek.</p>';
        }
    }

    updateUsersTable(data) {
        const tbody = document.getElementById('users-table-body');
        if (!tbody) return;
        if (data.users && data.users.length > 0) {
            tbody.innerHTML = data.users.map(user => `
                <tr>
                    <td>${user.fid}</td>
                    <td><strong>${this.escapeHtml(user.fnev)}</strong></td>
                    <td>${this.escapeHtml(user.email)}</td>
                    <td>${user.vnev || user.knev ? `${this.escapeHtml(user.vnev || '')} ${this.escapeHtml(user.knev || '')}` : '<em>Nincs megadva</em>'}</td>
                    <td><span class="admin-badge ${user.szerep === 'admin' ? 'admin-badge-warning' : 'admin-badge-secondary'}">${user.szerep}</span></td>
                    <td>${this.formatStatus(user.statusz)}</td>
                    <td>
                        ${Number(user.email_megerositve) ? '<span class="admin-badge admin-badge-success">Igen</span>' : '<span class="admin-badge admin-badge-warning">Nem</span>'}
                    </td>
                    <td>${this.escapeHtml(user.statusz_ok || '-')}</td>
                    <td>${user.statusz_meddig ? this.formatDate(user.statusz_meddig) : '-'}</td>
                    <td>${this.formatDate(user.regisztralt)}</td>
                    <td>
                        <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                            <button class="admin-btn admin-btn-sm admin-btn-primary" onclick="showEditModal(${user.fid})">Szerkesztés</button>
                            <button class="admin-btn admin-btn-sm admin-btn-info" onclick="showRoleModal(${user.fid}, '${user.szerep}', '${this.escapeHtml(user.fnev)}')">Szerep</button>
                            <button class="admin-btn admin-btn-sm admin-btn-warning" onclick="showStatusModal(${user.fid}, '${user.statusz}', '${this.escapeHtml(user.fnev)}')">Státusz</button>
                            ${user.szerep !== 'admin' ? `<button class="admin-btn admin-btn-sm admin-btn-danger" onclick="deleteUser(${user.fid}, '${this.escapeHtml(user.fnev)}')">Törlés</button>` : ''}
                        </div>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="11" style="text-align: center; padding: 2rem;">Nincsenek felhasználók.</td></tr>';
        }
    }

    updateAdminActivitiesTable(data) {
        const tbody = document.getElementById('admin-activities-table-body');
        if (!tbody) return;
        if (data.activities && data.activities.length > 0) {
            tbody.innerHTML = data.activities.map(activity => `
                <tr>
                    <td>${activity.atid}</td>
                    <td>${activity.admin_felhasznalo ? this.escapeHtml(activity.admin_felhasznalo) : '<em>Ismeretlen</em>'}</td>
                    <td>${activity.cel_felhasznalo ? this.escapeHtml(activity.cel_felhasznalo) : '<em>-</em>'}</td>
                    <td>${this.escapeHtml(activity.tevekenyseg)}</td>
                    <td>${this.escapeHtml(activity.reszletek || '-')}</td>
                    <td>${this.formatDate(activity.idopont)}</td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem;">Nincsenek admin tevékenységek.</td></tr>';
        }
    }

    updateUserActivitiesTable(data) {
        const tbody = document.getElementById('user-activities-table-body');
        if (!tbody) return;
        if (data.activities && data.activities.length > 0) {
            tbody.innerHTML = data.activities.map(activity => `
                <tr>
                    <td>${activity.ftid}</td>
                    <td>${activity.fnev ? `${this.escapeHtml(activity.fnev)}<br><small>${this.escapeHtml(activity.email)}</small>` : '<em>Ismeretlen</em>'}</td>
                    <td>${this.escapeHtml(activity.tevekenyseg)}</td>
                    <td><span class="admin-badge admin-badge-secondary">${this.formatActivityCategory(activity.kategoria)}</span></td>
                    <td>${this.formatDate(activity.idopont)}</td>
                    <td>${activity.sikeresseg === 'sikeres' ? '<span class="admin-badge admin-badge-success">Sikeres</span>' : '<span class="admin-badge admin-badge-danger">Sikertelen</span>'}</td>
                    <td>
                        <button class="admin-btn admin-btn-sm admin-btn-danger" onclick="deleteActivity(${activity.ftid}, '${this.escapeHtml(activity.tevekenyseg)}', '${this.escapeHtml(activity.fnev)}')">Törlés</button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 2rem;">Nincsenek felhasználói tevékenységek.</td></tr>';
        }
    }

    updateEmailVerificationsTable(data) {
        const tbody = document.getElementById('email-verifications-table-body');
        if (!tbody) return;
        if (data.verifications && data.verifications.length > 0) {
            tbody.innerHTML = data.verifications.map(verification => `
                <tr>
                    <td>${verification.emid}</td>
                    <td>${this.escapeHtml(verification.fnev)}</td>
                    <td>${this.escapeHtml(verification.email)}</td>
                    <td>${this.formatEmailStatus(verification)}</td>
                    <td>${this.formatDate(verification.lejarati_ido)}</td>
                    <td>${this.formatDate(verification.idopont)}</td>
                    <td>
                        <button class="admin-btn admin-btn-sm admin-btn-danger" onclick="deleteActivity(${verification.emid}, 'Email ellenőrzés', '${this.escapeHtml(verification.fnev)}')">Törlés</button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 2rem;">Nincsenek email ellenőrzések.</td></tr>';
        }
    }

    updatePasswordResetsTable(data) {
        const tbody = document.getElementById('password-resets-table-body');
        if (!tbody) return;
        if (data.resets && data.resets.length > 0) {
            tbody.innerHTML = data.resets.map(reset => `
                <tr>
                    <td>${reset.jvid}</td>
                    <td>${this.escapeHtml(reset.fnev)}</td>
                    <td>${this.escapeHtml(reset.email)}</td>
                    <td>${this.formatResetStatus(reset)}</td>
                    <td>${this.formatDate(reset.lejarati_ido)}</td>
                    <td>${this.formatDate(reset.idopont)}</td>
                    <td>
                        <button class="admin-btn admin-btn-sm admin-btn-danger" onclick="deleteActivity(${reset.jvid}, 'Jelszó visszaállítás', '${this.escapeHtml(reset.fnev)}')">Törlés</button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 2rem;">Nincsenek jelszó visszaállítások.</td></tr>';
        }
    }

    updateDeletedUsersTable(data) {
        const tbody = document.getElementById('deleted-users-table-body');
        if (!tbody) return;
        if (data.users && data.users.length > 0) {
            tbody.innerHTML = data.users.map(user => `
                <tr>
                    <td>${user.fid}</td>
                    <td>${this.escapeHtml(user.fnev)}</td>
                    <td>${this.escapeHtml(user.email)}</td>
                    <td>${user.vnev || user.knev ? `${this.escapeHtml(user.vnev || '')} ${this.escapeHtml(user.knev || '')}` : '<em>Nincs megadva</em>'}</td>
                    <td><span class="admin-badge ${user.szerep === 'admin' ? 'admin-badge-warning' : 'admin-badge-secondary'}">${user.szerep}</span></td>
                    <td>${this.formatDate(user.regisztralt)}</td>
                    <td>${this.formatDate(user.torles_idopontja)}</td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 2rem;">Nincsenek törölt felhasználók.</td></tr>';
        }
    }

    updateCommentsTable(data) {
        const tbody = document.getElementById('comments-table-body');
        if (!tbody) return;
        if (data.comments && data.comments.length > 0) {
            tbody.innerHTML = data.comments.map(comment => {
                let commentContent = (comment.megjegyzes && comment.megjegyzes.trim() !== '') ? this.truncateText(this.escapeHtml(comment.megjegyzes), 150) : '<em>Nincs megjegyzés</em>';
                return `
                    <tr data-comment-id="${comment.meid}">
                        <td><input type="checkbox" class="comment-checkbox" value="${comment.meid}" onchange="window.updateSelectedCount('comments')"></td>
                        <td>${comment.meid}</td>
                        <td>
                            ${comment.fnev ? `<strong>${this.escapeHtml(comment.fnev)}</strong><br><small>${this.escapeHtml(comment.email)}</small>` : `<em>ID: ${comment.fid}</em>`}
                        </td>
                        <td>D${comment.dolgozo_id}</td>
                        <td>${commentContent}</td>
                        <td>
                            <span style="color: #ffc107; white-space: nowrap; font-weight: bold;">
                                ${this.generateStars(comment.ertekeles)} (${comment.ertekeles}/5)
                            </span>
                        </td>
                        <td>${this.formatDate(comment.idopont)}</td>
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                <button class="admin-btn admin-btn-sm admin-btn-primary" onclick="window.showEditCommentModal(${comment.meid})">Szerkesztés</button>
                                <button class="admin-btn admin-btn-sm admin-btn-danger" onclick="showAdminDeleteCommentModal(${comment.meid})">Törlés</button>
                            </div>
                        </td>
                    </tr>`;
            }).join('');
        } else {
            tbody.innerHTML = `<tr><td colspan="8" style="text-align: center; padding: 2rem;">Nincsenek megjegyzések.</td></tr>`;
        }
        if (typeof window.updateSelectedCount === 'function') window.updateSelectedCount('comments');
    }

    updateDeletedCommentsTable(data) {
        const tbody = document.getElementById('deleted-comments-table-body');
        if (!tbody) return;
        if (data.comments && data.comments.length > 0) {
            tbody.innerHTML = data.comments.map(comment => {
                const deletionBadgeClass = comment.torles_tipusa === 'admin' ? 'admin-badge-primary' : (comment.torles_tipusa === 'felhasznalo' ? 'admin-badge-danger' : 'admin-badge-secondary');
                let commentContent = (comment.megjegyzes && comment.megjegyzes.trim() !== '') ? this.truncateText(this.escapeHtml(comment.megjegyzes), 150) : '<em>Nincs megjegyzés</em>';
                return `
                    <tr>
                        <td><input type="checkbox" class="deleted-comment-checkbox" value="${comment.meid}" onchange="window.updateSelectedCount('deleted-comments')"></td>
                        <td>${comment.meid}</td>
                        <td>
                            ${comment.fnev ? `<strong>${this.escapeHtml(comment.fnev)}</strong><br><small>${this.escapeHtml(comment.email)}</small>` : `<em>ID: ${comment.fid}</em>`}
                        </td>
                        <td>D${comment.dolgozo_id}</td>
                        <td>${commentContent}</td>
                        <td>
                            <span style="color: #ffc107; white-space: nowrap; font-weight: bold;">
                                ${this.generateStars(comment.ertekeles)} (${comment.ertekeles}/5)
                            </span>
                        </td>
                        <td>${this.formatDate(comment.idopont)}</td>
                        <td>${this.formatDate(comment.torles_datuma)}</td>
                        <td><span class="admin-badge ${deletionBadgeClass}">${comment.torles_tipusa}</span></td>
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                <button class="admin-btn admin-btn-sm admin-btn-danger" onclick="window.permanentDeleteComment(${comment.meid})">Végleges Törlés</button>
                            </div>
                        </td>
                    </tr>`;
            }).join('');
        } else {
             tbody.innerHTML = `<tr><td colspan="10" style="text-align: center; padding: 2rem;">Nincsenek törölt megjegyzések.</td></tr>`;
        }
        if (typeof window.updateSelectedCount === 'function') window.updateSelectedCount('deleted-comments');
    }

    generateStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += i <= rating ? '<i class="fas fa-star" style="color: #ffc107;"></i>' : '<i class="far fa-star" style="color: #ffc107;"></i>';
        }
        return stars;
    }

    truncateText(text, maxLength) {
        if (!text) return '';
        return text.length <= maxLength ? text : text.substring(0, maxLength) + '...';
    }

    formatStatus(status) {
        const statuses = { 'Aktiv': ['Aktív', 'admin-badge-success'], 'Fuggoben': ['Függőben', 'admin-badge-warning'], 'Kitiltott': ['Kitiltott', 'admin-badge-danger'] };
        return statuses[status] ? `<span class="admin-badge ${statuses[status][1]}">${statuses[status][0]}</span>` : `<span class="admin-badge admin-badge-secondary">${status}</span>`;
    }

    formatEmailStatus(verification) {
        if (verification.ellenorzve && verification.ellenorzve !== '0000-00-00 00:00:00') {
            return '<span class="admin-badge admin-badge-success">Megerősítve</span>';
        }
        if (!verification.lejarati_ido || verification.lejarati_ido === '0000-00-00 00:00:00') {
            return '<span class="admin-badge admin-badge-warning">Függőben</span>';
        }
        try {
            const lejaratiDate = new Date(verification.lejarati_ido);
            if (isNaN(lejaratiDate.getTime())) return '<span class="admin-badge admin-badge-warning">Függőben</span>';
            if (lejaratiDate < new Date()) return '<span class="admin-badge admin-badge-danger">Lejárt</span>';
            return '<span class="admin-badge admin-badge-warning">Függőben</span>';
        } catch (e) {
            return '<span class="admin-badge admin-badge-warning">Függőben</span>';
        }
    }

    formatResetStatus(reset) {
        if (Number(reset.felhasznalva)) {
            return '<span class="admin-badge admin-badge-success">Felhasználva</span>';
        }
        if (!reset.lejarati_ido || reset.lejarati_ido === '0000-00-00 00:00:00') {
            return '<span class="admin-badge admin-badge-warning">Aktív</span>';
        }
        try {
            const lejaratiDate = new Date(reset.lejarati_ido);
            if (isNaN(lejaratiDate.getTime())) return '<span class="admin-badge admin-badge-warning">Aktív</span>';
            if (lejaratiDate < new Date()) return '<span class="admin-badge admin-badge-danger">Lejárt</span>';
            return '<span class="admin-badge admin-badge-warning">Aktív</span>';
        } catch (e) {
            return '<span class="admin-badge admin-badge-warning">Aktív</span>';
        }
    }

    formatActivityCategory(c) {
        const cats = { 'bejelentkezes': 'Bejelentkezés', 'profil': 'Profil', 'biztonsag': 'Biztonság', 'egyeb': 'Egyéb' };
        return cats[c] || c;
    }

    formatDate(dateString) {
        if (!dateString || dateString === '0000-00-00 00:00:00' || dateString === '0000-00-00') return '-';
        try {
            const date = new Date(dateString);
            return isNaN(date.getTime()) ? '-' : date.toLocaleString('hu-HU');
        } catch (e) { return '-'; }
    }

    escapeHtml(text) {
        if (text === null || text === undefined) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    updatePagination(data) {
        const wrapper = document.querySelector(`#${this.currentTab}-content .admin-pagination-wrapper`);
        const paginationList = document.querySelector(`#${this.currentTab}-content .admin-pagination`);
        const infoDiv = document.querySelector(`#${this.currentTab}-content .pagination-info`);

        if (!wrapper || !paginationList) return;

        if (data.pages > 1) {
            wrapper.style.display = 'flex'; 
            const baseUrl = this.getBaseUrl();
            paginationList.innerHTML = this.generatePagination(data.current_page, data.pages, baseUrl);

            if (infoDiv && data.total !== undefined) {
                infoDiv.innerHTML = `<span>Oldal ${data.current_page} / ${data.pages}</span><span style="margin-left: 15px;">Összesen: ${data.total} elem</span>`;
            }

            paginationList.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const url = new URL(e.currentTarget.href);
                    this.refreshCurrentTab(url.searchParams.get('page'));
                });
            });
        } else {
            wrapper.style.display = 'none';
        }
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
        if (endPage - startPage + 1 < maxPagesToShow) startPage = Math.max(1, endPage - maxPagesToShow + 1);
        
        if (currentPage > 1) pagination += `<li><a href="${baseUrl}&page=${currentPage - 1}">&laquo;</a></li>`;
        if (startPage > 1) {
            pagination += `<li><a href="${baseUrl}&page=1">1</a></li>`;
            if (startPage > 2) pagination += '<li><span class="pagination-ellipsis">...</span></li>';
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const active = i == currentPage ? 'active' : '';
            pagination += `<li><a href="${baseUrl}&page=${i}" class="${active}">${i}</a></li>`;
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) pagination += '<li><span class="pagination-ellipsis">...</span></li>';
            pagination += `<li><a href="${baseUrl}&page=${totalPages}">${totalPages}</a></li>`;
        }
        if (currentPage < totalPages) pagination += `<li><a href="${baseUrl}&page=${currentPage + 1}">&raquo;</a></li>`;
        
        return pagination;
    }

    initEventListeners() {
        document.querySelectorAll('.admin-nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                this.switchTab(e.currentTarget.getAttribute('data-tab'));
            });
        });

        document.querySelectorAll('.admin-search-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitSearch(form);
            });
        });
    }

    switchTab(tab) {
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        url.searchParams.delete('page');
        window.history.pushState({}, '', url);

        this.currentTab = tab;
        this.showCurrentTab();
        this.refreshCurrentTab(1);
    }

    showCurrentTab() {
        document.querySelectorAll('.admin-tab-content').forEach(content => content.classList.remove('active'));
        const currentContent = document.getElementById(`${this.currentTab}-content`);
        if (currentContent) currentContent.classList.add('active');

        document.querySelectorAll('.admin-nav-link').forEach(link => link.classList.remove('active'));
        const currentLink = document.querySelector(`.admin-nav-link[data-tab="${this.currentTab}"]`);
        if (currentLink) currentLink.classList.add('active');
    }

    submitSearch(form) {
        const url = new URL(window.location);
        url.search = new URLSearchParams(new FormData(form)).toString();
        window.location.href = url.toString();
    }

    initSorting() {
        document.querySelectorAll('.admin-table th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const column = th.getAttribute('data-column');
                const currentOrder = th.classList.contains('sort-asc') ? 'ASC' : 'DESC';
                const newOrder = currentOrder === 'ASC' ? 'DESC' : 'ASC';
                const url = new URL(window.location);
                url.searchParams.set('order_by', column);
                url.searchParams.set('order_dir', newOrder);
                url.searchParams.delete('page');
                window.location.href = url.toString();
            });
        });
    }

    initModals() {
        document.querySelectorAll('.admin-modal').forEach(modal => {
            modal.addEventListener('click', (e) => { if (e.target === modal) this.closeModal(modal); });
        });
        document.querySelectorAll('.admin-modal-close').forEach(btn => {
            btn.addEventListener('click', (e) => { this.closeModal(e.target.closest('.admin-modal')); });
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') document.querySelectorAll('.admin-modal.show').forEach(m => this.closeModal(m));
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
            document.addEventListener('click', (e) => {
                if (!userInfo.contains(e.target) && !dropdown.contains(e.target)) dropdown.classList.remove('show');
            });
        }
    }

    initThemeToggle() {
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            const savedTheme = localStorage.getItem('admin-theme') || 'light';
            this.setTheme(savedTheme);
            themeToggle.checked = savedTheme === 'dark';

            themeToggle.addEventListener('change', (e) => {
                const theme = e.target.checked ? 'dark' : 'light';
                this.setTheme(theme);
                localStorage.setItem('admin-theme', theme);
                e.preventDefault();
            });
        }
    }

    setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        if (theme === 'dark') {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }
    }

    closeModal(modal) {
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
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
            const response = await fetch(`?action=get_user_data&user_id=${userId}`);
            const data = await response.json();
            if (data.success) {
                const u = data.user;
                document.getElementById('edit_user_id').value = userId;
                document.getElementById('edit_fnev').value = u.fnev || '';
                document.getElementById('edit_email').value = u.email || '';
                document.getElementById('edit_knev').value = u.knev || '';
                document.getElementById('edit_vnev').value = u.vnev || '';
                document.getElementById('edit_nem').value = u.nem || 'nem_publikus';
                document.getElementById('edit_szuletett').value = u.szuletett ? new Date(u.szuletett).toISOString().split('T')[0] : '';
                document.getElementById('edit_telefon').value = u.telefon || '';
                document.getElementById('edit_varos').value = u.varmegye || '';
                document.getElementById('edit_reszletek').value = u.reszletek || '';
                this.showModal('editModal');
            }
        } catch (e) { console.error(e); }
    }

    showRoleModal(userId, currentRole, userName) {
        document.getElementById('role_user_id').value = userId;
        document.getElementById('role_user_name').textContent = userName;
        document.getElementById('makeAdminBtn').style.display = currentRole === 'admin' ? 'none' : 'block';
        document.getElementById('makeUserBtn').style.display = currentRole === 'admin' ? 'block' : 'none';
        this.showModal('roleModal');
    }

    deleteUser(userId, userName) {
        if (confirm(`Biztosan törölni szeretnéd a(z) "${userName}" felhasználót?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="action" value="delete_user"><input type="hidden" name="user_id" value="${userId}">`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    deleteActivity(activityId, activityName, userName = '') {
        const msg = userName ? `Biztosan törölni szeretnéd a(z) "${userName}" felhasználó "${activityName}" tevékenységét?` : `Biztosan törölni szeretnéd a(z) "${activityName}" tevékenységet?`;
        if (confirm(msg)) {
            const form = document.createElement('form');
            form.method = 'POST';
            const tableMap = { 'user-activities': 'felhasznalo_tevekenyseg', 'admin-activities': 'admin_tevekenyseg', 'email-verifications': 'email_ell', 'password-resets': 'jelszo_visszaallitasok', 'deleted-users': 'torolt_felhasznalok' };
            const table = tableMap[this.currentTab] || 'felhasznalo_tevekenyseg';
            form.innerHTML = `<input type="hidden" name="action" value="delete_activity"><input type="hidden" name="activity_id" value="${activityId}"><input type="hidden" name="table" value="${table}">`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    showAdminDeleteCommentModal(commentId) {
        document.getElementById('admin_delete_comment_id').value = commentId;
        this.showModal('adminDeleteCommentModal');
    }

    closeAdminDeleteCommentModal() {
        this.closeModal(document.getElementById('adminDeleteCommentModal'));
        document.getElementById('admin_delete_comment_id').value = '';
        document.getElementById('adminDeleteCommentForm').reset();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.admin = new WebAdmin();
});

window.showStatusModal = (userId, currentStatus, userName) => { if(window.admin) window.admin.showStatusModal(userId, currentStatus, userName); };
window.showEditModal = (userId) => { if(window.admin) window.admin.showEditModal(userId); };
window.showRoleModal = (userId, currentRole, userName) => { if(window.admin) window.admin.showRoleModal(userId, currentRole, userName); };
window.deleteUser = (userId, userName) => { if(window.admin) window.admin.deleteUser(userId, userName); };
window.deleteActivity = (activityId, activityName, userName = '') => { if(window.admin) window.admin.deleteActivity(activityId, activityName, userName); };
window.showAdminDeleteCommentModal = (id) => { if(window.admin) window.admin.showAdminDeleteCommentModal(id); };
window.closeAdminDeleteCommentModal = () => { if(window.admin) window.admin.closeAdminDeleteCommentModal(); };

window.toggleSelectAll = function(source, type) {
    const selector = type === 'comments' ? '.comment-checkbox' : '.deleted-comment-checkbox';
    const checkboxes = document.querySelectorAll(selector);
    checkboxes.forEach(cb => { cb.checked = source.checked; });
    window.updateSelectedCount(type);
};

window.updateSelectedCount = function(type) {
    const selector = type === 'comments' ? '.comment-checkbox' : '.deleted-comment-checkbox';
    const countDisplayId = type === 'comments' ? 'selected-comments-count' : 'selected-deleted-comments-count';
    const selectAllId = type === 'comments' ? 'select-all-comments' : 'select-all-deleted-comments';
    
    const checkboxes = document.querySelectorAll(selector);
    const selectedCount = document.querySelectorAll(`${selector}:checked`).length;
    
    const displayElement = document.getElementById(countDisplayId);
    if (displayElement) displayElement.textContent = selectedCount;
    
    const selectAllCheckbox = document.getElementById(selectAllId);
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = selectedCount === checkboxes.length && checkboxes.length > 0;
    }
};

window.submitBulkAction = function(type) {
    const selector = type === 'comments' ? '.comment-checkbox:checked' : '.deleted-comment-checkbox:checked';
    const selectedIds = Array.from(document.querySelectorAll(selector)).map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        alert('Kérlek válassz ki legalább egy elemet a csoportos művelethez!');
        return;
    }

    const actionName = type === 'comments' ? 'bulk_delete_comments' : 'bulk_permanent_delete_comments';
    const actionText = type === 'comments' ? 'törölni' : 'VÉGLEGESEN törölni';
    
    if (confirm(`Biztosan szeretnéd ${actionText} a kiválasztott ${selectedIds.length} megjegyzést?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="${actionName}">
            <input type="hidden" name="selected_ids" value="${selectedIds.join(',')}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
};

window.showEditCommentModal = async function(commentId) {
    try {
        const response = await fetch(`?action=get_comment&comment_id=${commentId}`);
        const data = await response.json();
        if (data.success) {
            document.getElementById('edit_comment_id').value = data.comment.meid;
            document.getElementById('edit_comment_text').value = data.comment.megjegyzes;
            document.getElementById('edit_comment_rating').value = data.comment.ertekeles;
            window.admin.showModal('editCommentModal');
        }
    } catch (e) {
        console.error(e);
    }
};

window.closeEditCommentModal = function() {
    window.admin.closeModal(document.getElementById('editCommentModal'));
};

window.permanentDeleteComment = function(commentId) {
    if (confirm('Biztosan VÉGLEGESEN törölni szeretnéd ezt a megjegyzést? Ez a művelet nem vonható vissza!')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="permanent_delete_comment">
            <input type="hidden" name="meid" value="${commentId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
};

window.addEventListener('popstate', () => {
    if (window.admin) {
        window.admin.currentTab = window.admin.getCurrentTab();
        window.admin.showCurrentTab();
        window.admin.refreshCurrentTab();
    }
});