<aside class="w-64 bg-gray-800 border-r border-gray-700 hidden md:block">
    <div class="h-full flex flex-col">
        <nav class="flex-1 px-4 py-6">
            <ul class="space-y-2">
                <li>
                    <a href="index.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'bg-gray-700 text-white' : ''; ?>">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3 text-primary"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="messages.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) === 'messages.php' ? 'bg-gray-700 text-white' : ''; ?>">
                        <i class="fas fa-envelope w-5 h-5 mr-3 text-primary"></i>
                        <span>Messages</span>
                        <?php if (isset($new_messages) && $new_messages > 0): ?>
                            <span class="ml-auto bg-secondary text-white text-xs font-medium px-2 py-0.5 rounded-full">
                                <?php echo $new_messages; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="border-t border-gray-700 pt-2 mt-2">
                    <a href="logout.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt w-5 h-5 mr-3 text-primary"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="p-4 border-t border-gray-700">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                    <span class="text-white font-medium"><?php echo substr($_SESSION['admin_username'], 0, 1); ?></span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
                    <p class="text-xs text-gray-400">Administrator</p>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Toggle -->
<div class="md:hidden fixed bottom-4 right-4 z-50">
    <button id="mobileSidebarToggle" class="w-12 h-12 rounded-full bg-primary shadow-lg flex items-center justify-center">
        <i class="fas fa-bars text-white"></i>
    </button>
</div>

<!-- Mobile Sidebar -->
<div id="mobileSidebar" class="fixed inset-0 z-40 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" id="sidebarOverlay"></div>
    
    <div class="absolute inset-y-0 left-0 w-64 bg-gray-800 shadow-xl transform translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="flex justify-between items-center p-4 border-b border-gray-700">
            <h2 class="text-xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Admin Panel</h2>
            <button id="closeMobileSidebar" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <nav class="px-4 py-6">
            <ul class="space-y-2">
                <li>
                    <a href="index.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'bg-gray-700 text-white' : ''; ?>">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3 text-primary"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="messages.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) === 'messages.php' ? 'bg-gray-700 text-white' : ''; ?>">
                        <i class="fas fa-envelope w-5 h-5 mr-3 text-primary"></i>
                        <span>Messages</span>
                        <?php if (isset($new_messages) && $new_messages > 0): ?>
                            <span class="ml-auto bg-secondary text-white text-xs font-medium px-2 py-0.5 rounded-full">
                                <?php echo $new_messages; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="border-t border-gray-700 pt-2 mt-2">
                    <a href="logout.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt w-5 h-5 mr-3 text-primary"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script>
    // Mobile sidebar functionality
    const mobileSidebar = document.getElementById('mobileSidebar');
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const closeMobileSidebar = document.getElementById('closeMobileSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    mobileSidebarToggle.addEventListener('click', function() {
        mobileSidebar.classList.remove('-translate-x-full');
    });
    
    function closeSidebar() {
        mobileSidebar.classList.add('-translate-x-full');
    }
    
    closeMobileSidebar.addEventListener('click', closeSidebar);
    sidebarOverlay.addEventListener('click', closeSidebar);
</script>