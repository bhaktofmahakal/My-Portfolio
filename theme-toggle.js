/**
 * Enhanced Theme Toggle Functionality
 * - Supports both desktop and mobile theme toggles
 * - Persists theme preference using localStorage
 * - Applies theme immediately on page load to prevent flash
 * - Synchronizes theme state across all toggle elements
 */
(function() {
    // Run immediately and also wait for DOM to be fully loaded
    const initThemeToggle = function() {
        console.log('Initializing theme toggle...');
    
        // Theme toggle elements
        const themeToggles = document.querySelectorAll('.theme-toggle');
        const mobileThemeToggle = document.querySelector('#mobile-theme-toggle');
        const body = document.body;
        const html = document.documentElement;
        
        console.log('Theme toggle button found:', themeToggles.length > 0);
    
        /**
         * Updates the theme across the entire site
         * @param {boolean} isDark - Whether to apply dark theme
         */
        function updateTheme(isDark) {
            // Apply or remove theme classes
            if (isDark) {
                body.classList.add('dark-theme');
                html.classList.add('dark-theme');
                html.classList.add('dark'); // For Tailwind dark mode
                localStorage.setItem('theme', 'dark');
            } else {
                body.classList.remove('dark-theme');
                html.classList.remove('dark-theme');
                html.classList.remove('dark'); // For Tailwind dark mode
                localStorage.setItem('theme', 'light');
            }
            
            // Update all theme toggle buttons
            themeToggles.forEach(toggle => {
                const icon = toggle.querySelector('i');
                const label = toggle.querySelector('.theme-label');
                
                if (isDark) {
                    if (icon) icon.className = 'fas fa-sun text-primary';
                    if (label) label.textContent = 'Light Mode';
                } else {
                    if (icon) icon.className = 'fas fa-moon text-primary';
                    if (label) label.textContent = 'Dark Mode';
                }
            });
            
            // Update mobile toggle switch if it exists
            if (mobileThemeToggle) {
                mobileThemeToggle.checked = isDark;
            }
        }
    
        // Initialize theme based on user preference or system preference
        function initializeTheme() {
            // Check localStorage first
            const savedTheme = localStorage.getItem('theme');
            
            if (savedTheme === 'dark') {
                updateTheme(true);
            } else if (savedTheme === 'light') {
                updateTheme(false);
            } else {
                // If no saved preference, check system preference
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                updateTheme(prefersDark);
            }
        }
    
        // Initialize theme immediately
        initializeTheme();
        
        // Add event listeners to all theme toggles
        console.log('Theme toggles found:', themeToggles.length);
        themeToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                console.log('Theme toggle clicked');
                const isDark = !body.classList.contains('dark-theme');
                updateTheme(isDark);
            });
        });
        
        // Mobile theme toggle
        if (mobileThemeToggle) {
            mobileThemeToggle.addEventListener('change', function() {
                updateTheme(this.checked);
            });
        }
        
        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            // Only update if user hasn't set a preference
            if (!localStorage.getItem('theme')) {
                updateTheme(e.matches);
            }
        });
    };

    // Run the function immediately
    initThemeToggle();

    // Also run when DOM is fully loaded to ensure all elements are available
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initThemeToggle);
    }
})();