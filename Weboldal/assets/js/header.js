document.addEventListener('DOMContentLoaded', function() {
    console.log('Header JS loaded - Final solution');

    const profileDropdown = document.querySelector('.header_profile_dropdown');
    const profileLink = document.querySelector('.profile-link');
    const dropdownMenu = document.querySelector('.header_dropdown_menu');
    
    if (profileDropdown && profileLink && dropdownMenu) {
        profileLink.addEventListener('click', function(e) {
            e.preventDefault();
            dropdownMenu.classList.toggle('active');
            e.stopPropagation();
        });
        
        document.addEventListener('click', function(e) {
            if (!profileDropdown.contains(e.target)) {
                dropdownMenu.classList.remove('active');
            }
        });
    }

    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenuContent = document.querySelector('.mobile-menu-content');
    const mobileCloseButton = document.querySelector('.mobile-close-button');
    
    if (mobileMenuToggle && mobileMenuContent && mobileCloseButton) {
        console.log('Mobile menu elements found');
        
        const overlay = document.createElement('div');
        overlay.className = 'mobile-menu-overlay';
        document.body.appendChild(overlay);
        
        const openMenu = function() {
            console.log('Opening menu');
            mobileMenuToggle.classList.add('active');
            mobileMenuContent.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        };
        
        const closeMenu = function() {
            console.log('Closing menu');
            mobileMenuToggle.classList.remove('active');
            mobileMenuContent.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            
            const mobileProfileSection = document.querySelector('.mobile-profile-section');
            if (mobileProfileSection) {
                mobileProfileSection.classList.remove('active');
            }
        };
        
        mobileMenuToggle.addEventListener('click', function(e) {
            console.log('Hamburger clicked');
            e.stopPropagation();
            if (mobileMenuContent.classList.contains('active')) {
                closeMenu();
            } else {
                openMenu();
            }
        });
        
        mobileCloseButton.addEventListener('click', function(e) {
            console.log('Close button clicked');
            e.stopPropagation();
            closeMenu();
        });
        
        overlay.addEventListener('click', closeMenu);
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenuContent.classList.contains('active')) {
                closeMenu();
            }
        });
        
        const mobileProfileHeader = document.querySelector('.mobile-profile-header');
        const mobileProfileSection = document.querySelector('.mobile-profile-section');
        
        if (mobileProfileHeader && mobileProfileSection) {
            mobileProfileHeader.addEventListener('click', function(e) {
                console.log('Profile header clicked');
                e.stopPropagation();
                mobileProfileSection.classList.toggle('active');
            });
            
            mobileMenuContent.addEventListener('click', function(e) {
                if (!e.target.closest('.mobile-profile-section') && mobileProfileSection.classList.contains('active')) {
                    mobileProfileSection.classList.remove('active');
                }
            });
        }
        
        mobileMenuContent.addEventListener('click', function(e) {
            if (e.target.closest('.mobile-profile-header')) {
                return;
            }
            
            if (e.target.closest('.mobile-close-button')) {
                return;
            }
            
            if (e.target.closest('.mobile-logo-link')) {
                return;
            }
            
            if (e.target.tagName === 'A' && e.target.closest('.mobile-profile-menu')) {
                closeMenu();
                return;
            }
            
            if (e.target.tagName === 'A') {
                closeMenu();
            }
        });
    } else {
        console.log('Mobile menu elements NOT found:', {
            mobileMenuToggle,
            mobileMenuContent,
            mobileCloseButton
        });
    }
});