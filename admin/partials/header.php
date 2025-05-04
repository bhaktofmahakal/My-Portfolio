<header class="bg-gray-800 border-b border-gray-700 shadow-lg">
    <div class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="index.php" class="text-xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent flex items-center">
                    <i class="fas fa-user-shield mr-2"></i>
                    Admin Panel
                </a>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Theme Toggle -->
                <button id="themeToggle" class="p-2 rounded-full bg-gray-700 hover:bg-gray-600 transition-colors">
                    <i class="fas fa-moon text-primary"></i>
                </button>
                
                <!-- Admin Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                            <span class="text-white font-medium"><?php echo substr($_SESSION['admin_username'], 0, 1); ?></span>
                        </div>
                        <span class="hidden md:inline-block"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-gray-700 rounded-md shadow-lg py-1 z-50" style="display: none;">
                        <a href="logout.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Alpine.js for dropdown functionality -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    // Theme toggle functionality
    document.getElementById('themeToggle').addEventListener('click', function() {
        document.documentElement.classList.toggle('dark');
        
        // Store preference
        if (document.documentElement.classList.contains('dark')) {
            localStorage.setItem('theme', 'dark');
            this.querySelector('i').classList.remove('fa-moon');
            this.querySelector('i').classList.add('fa-sun');
        } else {
            localStorage.setItem('theme', 'light');
            this.querySelector('i').classList.remove('fa-sun');
            this.querySelector('i').classList.add('fa-moon');
        }
    });
    
    // Check for saved theme preference
    if (localStorage.getItem('theme') === 'dark' || 
        (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        document.getElementById('themeToggle').querySelector('i').classList.remove('fa-moon');
        document.getElementById('themeToggle').querySelector('i').classList.add('fa-sun');
    } else {
        document.documentElement.classList.remove('dark');
        document.getElementById('themeToggle').querySelector('i').classList.remove('fa-sun');
        document.getElementById('themeToggle').querySelector('i').classList.add('fa-moon');
    }
</script>