// Responsive JavaScript for FashionHub

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle functionality
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNav = document.querySelector('.main-nav');
    
    if (mobileMenuToggle && mainNav) {
        mobileMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            mainNav.classList.toggle('active');
            
            // Toggle hamburger icon
            const icon = this.querySelector('i');
            if (mainNav.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mainNav.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                if (mainNav.classList.contains('active')) {
                    mainNav.classList.remove('active');
                    const icon = mobileMenuToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
        
        // Prevent menu from closing when clicking inside it
        mainNav.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Close mobile menu when window is resized to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 992) {
                mainNav.classList.remove('active');
                const icon = mobileMenuToggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
                
                // Reset mega menu states
                const megaMenuTriggers = document.querySelectorAll('.mega-menu-trigger');
                megaMenuTriggers.forEach(trigger => {
                    trigger.classList.remove('active');
                });
            }
        });
    }
    
    // Mobile mega menu toggle
    const megaMenuTriggers = document.querySelectorAll('.mega-menu-trigger');
    megaMenuTriggers.forEach(trigger => {
        const link = trigger.querySelector('.mega-menu-link');
        const megaMenu = trigger.querySelector('.mega-menu');

        if (link && megaMenu) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Close other mega menus
                    megaMenuTriggers.forEach(otherTrigger => {
                        if (otherTrigger !== trigger) {
                            otherTrigger.classList.remove('active');
                        }
                    });

                    // Toggle current mega menu
                    trigger.classList.toggle('active');
                }
            });

            // Close mega menu when clicking on a link inside it
            const megaMenuLinks = megaMenu.querySelectorAll('a');
            megaMenuLinks.forEach(menuLink => {
                menuLink.addEventListener('click', function() {
                    trigger.classList.remove('active');
                });
            });
        }
    });
    
    // Account page sidebar auto-scroll functionality
    if (window.location.pathname.includes('account.php')) {
        setupAccountPageResponsive();
    }
});

function setupAccountPageResponsive() {
    // Wait for the account content to be loaded
    const checkAccountContent = setInterval(() => {
        const accountMenu = document.querySelector('.account-menu');
        const accountTabs = document.querySelectorAll('.account-tab');
        
        if (accountMenu && accountTabs.length > 0) {
            clearInterval(checkAccountContent);
            
            // Add click handlers for sidebar navigation
            const menuLinks = accountMenu.querySelectorAll('a[href^="#"]');
            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href').substring(1);
                    const targetTab = document.getElementById(targetId);
                    
                    if (targetTab) {
                        // Update active states
                        menuLinks.forEach(l => l.parentElement.classList.remove('active'));
                        this.parentElement.classList.add('active');
                        
                        accountTabs.forEach(tab => tab.classList.remove('active'));
                        targetTab.classList.add('active');
                        
                        // Auto-scroll on mobile (screens < 992px)
                        if (window.innerWidth < 992) {
                            const accountMain = document.querySelector('.account-main');
                            if (accountMain) {
                                // Scroll to the account-main container
                                accountMain.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }
                        }
                    }
                });
            });
            
            // Handle hash navigation on page load
            const hash = window.location.hash;
            if (hash) {
                const targetTab = document.querySelector(hash);
                const targetLink = document.querySelector(`a[href="${hash}"]`);
                
                if (targetTab && targetLink) {
                    // Update active states
                    menuLinks.forEach(l => l.parentElement.classList.remove('active'));
                    targetLink.parentElement.classList.add('active');
                    
                    accountTabs.forEach(tab => tab.classList.remove('active'));
                    targetTab.classList.add('active');
                    
                    // Auto-scroll on mobile
                    if (window.innerWidth < 992) {
                        setTimeout(() => {
                            const accountMain = document.querySelector('.account-main');
                            if (accountMain) {
                                accountMain.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }
                        }, 100);
                    }
                }
            }
        }
    }, 100);
}

// Handle window resize for responsive behavior
window.addEventListener('resize', function() {
    // Reset mobile menu state on resize
    const mainNav = document.querySelector('.main-nav');
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    
    if (mainNav && mobileMenuToggle && window.innerWidth > 992) {
        mainNav.classList.remove('active');
        const icon = mobileMenuToggle.querySelector('i');
        if (icon) {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    }
    
    // Reset mega menu states
    const megaMenuTriggers = document.querySelectorAll('.mega-menu-trigger');
    megaMenuTriggers.forEach(trigger => {
        if (window.innerWidth > 992) {
            trigger.classList.remove('active');
        }
    });
});