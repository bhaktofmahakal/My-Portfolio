<?php
require_once '../includes/config.php';

// Require login
require_login();

// Get new message count for sidebar
$query = "SELECT COUNT(*) as new_count FROM messages WHERE status = 'new'";
$result = mysqli_query($conn, $query);
$new_messages = mysqli_fetch_assoc($result)['new_count'];

// Get message ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: messages.php');
    exit;
}

$message_id = (int)$_GET['id'];

// Get message details
$query = "SELECT * FROM messages WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $message_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    header('Location: messages.php');
    exit;
}

$message = mysqli_fetch_assoc($result);

// Process reply form
$reply_sent = false;
$reply_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'])) {
    verify_csrf_token();
    
    $reply_text = sanitize_input($_POST['reply']);
    $to_email = $message['email'];
    $subject = "Re: " . $message['subject'];
    
    // In a real application, you would send an email here
    // For demonstration purposes, we'll just simulate success
    $reply_sent = true;
    
    // Mark message as handled
    $update_query = "UPDATE messages SET status = 'handled' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "i", $message_id);
    mysqli_stmt_execute($stmt);
    
    // Refresh message data
    $query = "SELECT * FROM messages WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $message_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $message = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message | Admin Dashboard</title>
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
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">View Message</h1>
                
                <div class="flex space-x-2">
                    <a href="messages.php" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-sm flex items-center transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Messages
                    </a>
                </div>
            </div>
            
            <!-- Message Details -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-xl font-bold"><?php echo htmlspecialchars($message['subject']); ?></h2>
                        <div class="flex items-center mt-2 text-sm text-gray-400">
                            <span class="mr-4">From: <span class="text-white"><?php echo htmlspecialchars($message['name']); ?> &lt;<?php echo htmlspecialchars($message['email']); ?>&gt;</span></span>
                            <span>Date: <span class="text-white"><?php echo date('M d, Y \a\t h:i A', strtotime($message['created_at'])); ?></span></span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="process_message.php?action=toggle_status&id=<?php echo $message['id']; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" 
                           class="px-3 py-1 rounded text-sm transition-colors <?php echo $message['status'] === 'new' ? 'bg-accent hover:bg-accent/90' : 'bg-secondary hover:bg-secondary/90'; ?>">
                            <?php echo $message['status'] === 'new' ? 'Mark as Handled' : 'Mark as New'; ?>
                        </a>
                        <a href="process_message.php?action=delete&id=<?php echo $message['id']; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" 
                           class="px-3 py-1 bg-red-600 hover:bg-red-700 rounded text-sm transition-colors"
                           onclick="return confirm('Are you sure you want to delete this message?')">
                            Delete
                        </a>
                    </div>
                </div>
                
                <div class="border-t border-gray-700 pt-6">
                    <div class="prose prose-invert max-w-none">
                        <p class="whitespace-pre-line"><?php echo htmlspecialchars($message['message']); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Reply Form -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-6">Reply to Message</h2>
                
                <?php if ($reply_sent): ?>
                    <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-500 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline">Your reply has been sent successfully!</span>
                    </div>
                <?php endif; ?>
                
                <?php if ($reply_error): ?>
                    <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-500 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline"><?php echo $reply_error; ?></span>
                    </div>
                <?php endif; ?>
                
                <form action="" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="mb-6">
                        <label for="reply" class="block text-sm font-medium text-gray-400 mb-2">Your Reply</label>
                        <textarea id="reply" name="reply" rows="6" required
                                  class="block w-full px-4 py-3 border border-gray-700 bg-gray-700 rounded-md focus:outline-none focus:ring-primary focus:border-primary"
                                  placeholder="Type your reply here..."></textarea>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-primary hover:bg-primary/90 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i> Send Reply
                        </button>
                    </div>
                </form>
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