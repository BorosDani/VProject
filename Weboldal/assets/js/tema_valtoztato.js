class ThemeSwitcher {
    constructor() {
        this.themeToggle = document.getElementById('theme-toggle-checkbox');
        this.mobileThemeToggle = document.getElementById('mobile-theme-toggle-checkbox');
        this.currentTheme = localStorage.getItem('theme') || 'light';
        
        this.init();
    }
    
    init() {
        this.applyTheme(this.currentTheme);
        
        if (this.themeToggle) {
            this.themeToggle.checked = this.currentTheme === 'dark';
            this.themeToggle.addEventListener('change', () => this.toggleTheme());
        }
        
        if (this.mobileThemeToggle) {
            this.mobileThemeToggle.checked = this.currentTheme === 'dark';
            this.mobileThemeToggle.addEventListener('change', () => this.toggleTheme());
        }
        
        this.syncToggleButtons();
    }
    
    toggleTheme() {
        this.currentTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.applyTheme(this.currentTheme);
        this.saveTheme();
        this.syncToggleButtons();
    }
    
    applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        
        const mobileThemeText = document.querySelector('.mobile-theme-toggle span:first-child');
        if (mobileThemeText) {
            mobileThemeText.textContent = theme === 'light' ? 'Világos téma' : 'Sötét téma';
        }
    }
    
    saveTheme() {
        localStorage.setItem('theme', this.currentTheme);
    }
    
    syncToggleButtons() {
        const isDark = this.currentTheme === 'dark';
        
        if (this.themeToggle) {
            this.themeToggle.checked = isDark;
        }
        
        if (this.mobileThemeToggle) {
            this.mobileThemeToggle.checked = isDark;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new ThemeSwitcher();
});

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new ThemeSwitcher();
    });
} else {
    new ThemeSwitcher();
}