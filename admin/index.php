<?php
require_once '../includes/config.php';

// Require login
require_login();

// Get total message count
$query = "SELECT COUNT(*) as total FROM messages";
$result = mysqli_query($conn, $query);
$total_messages = mysqli_fetch_assoc($result)['total'];

// Get new message count
$query = "SELECT COUNT(*) as new_count FROM messages WHERE status = 'new'";
$result = mysqli_query($conn, $query);
$new_messages = mysqli_fetch_assoc($result)['new_count'];

// Get latest 5 messages
$query = "SELECT * FROM messages ORDER BY created_at DESC LIMIT 5";
$latest_messages = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6c63ff',
                        secondary: '#f50057',
                        accent: '#00bfa6',
                        dark: '#121212',
                        light: '#f8f9fa'
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif']
                    }
                }
            },
            darkMode: 'class'
        }
    </script>
</head>
<body class="bg-gray-900 text-white font-poppins min-h-screen flex flex-col">
    <!-- Admin Header -->
    <?php include 'partials/header.php'; ?>
    
    <div class="flex flex-1">
        <!-- Sidebar -->
        <?php include 'partials/sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold">Dashboard</h1>
                <p class="text-gray-400">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-primary">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-primary bg-opacity-20 mr-4">
                            <i class="fas fa-envelope text-primary text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Total Messages</p>
                            <h3 class="text-2xl font-bold"><?php echo $total_messages; ?></h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-secondary">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-secondary bg-opacity-20 mr-4">
                            <i class="fas fa-bell text-secondary text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">New Messages</p>
                            <h3 class="text-2xl font-bold"><?php echo $new_messages; ?></h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-accent">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-accent bg-opacity-20 mr-4">
                            <i class="fas fa-check-circle text-accent text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Handled Messages</p>
                            <h3 class="text-2xl font-bold"><?php echo $total_messages - $new_messages; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Latest Messages -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">Latest Messages</h2>
                    <a href="messages.php" class="text-primary hover:text-primary/80 text-sm flex items-center">
                        View All <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                
                <?php if (mysqli_num_rows($latest_messages) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-gray-700 rounded-lg overflow-hidden">
                            <thead class="bg-gray-600">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                <?php while ($message = mysqli_fetch_assoc($latest_messages)): ?>
                                    <tr class="hover:bg-gray-600 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium"><?php echo htmlspecialchars($message['name']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm"><?php echo htmlspecialchars($message['email']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm truncate max-w-[200px]"><?php echo htmlspecialchars($message['subject']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm"><?php echo date('M d, Y', strtotime($message['created_at'])); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if ($message['status'] === 'new'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-secondary bg-opacity-20 text-secondary">
                                                    New
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-accent bg-opacity-20 text-accent">
                                                    Handled
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="view_message.php?id=<?php echo $message['id']; ?>" class="text-primary hover:text-primary/80 mr-3">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-4"></i>
                        <p>No messages yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <script>
        // Check for dark mode preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }
    </script>
</body>
</html>