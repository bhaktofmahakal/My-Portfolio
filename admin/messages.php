<?php
require_once '../includes/config.php';

// Require login
require_login();

// Get new message count for sidebar
$query = "SELECT COUNT(*) as new_count FROM messages WHERE status = 'new'";
$result = mysqli_query($conn, $query);
$new_messages = mysqli_fetch_assoc($result)['new_count'];

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Search and filters
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? sanitize_input($_GET['status']) : '';
$sort_by = isset($_GET['sort']) ? sanitize_input($_GET['sort']) : 'created_at';
$sort_order = isset($_GET['order']) ? sanitize_input($_GET['order']) : 'DESC';

// Validate sort parameters
$allowed_sort_fields = ['name', 'email', 'subject', 'created_at', 'status'];
if (!in_array($sort_by, $allowed_sort_fields)) {
    $sort_by = 'created_at';
}

$allowed_sort_orders = ['ASC', 'DESC'];
if (!in_array(strtoupper($sort_order), $allowed_sort_orders)) {
    $sort_order = 'DESC';
}

// Build query
$where_clauses = [];
$params = [];
$param_types = '';

if (!empty($search)) {
    $where_clauses[] = "(name LIKE ? OR email LIKE ? OR subject LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $param_types .= 'sss';
}

if (!empty($status_filter)) {
    $where_clauses[] = "status = ?";
    $params[] = $status_filter;
    $param_types .= 's';
}

$where_clause = '';
if (!empty($where_clauses)) {
    $where_clause = "WHERE " . implode(' AND ', $where_clauses);
}

// Count total messages for pagination
$count_query = "SELECT COUNT(*) as total FROM messages $where_clause";
if (!empty($params)) {
    $stmt = mysqli_prepare($conn, $count_query);
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
    mysqli_stmt_execute($stmt);
    $count_result = mysqli_stmt_get_result($stmt);
    $total_messages = mysqli_fetch_assoc($count_result)['total'];
} else {
    $count_result = mysqli_query($conn, $count_query);
    $total_messages = mysqli_fetch_assoc($count_result)['total'];
}

$total_pages = ceil($total_messages / $per_page);

// Get messages
$query = "SELECT * FROM messages $where_clause ORDER BY $sort_by $sort_order LIMIT $offset, $per_page";
if (!empty($params)) {
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
    mysqli_stmt_execute($stmt);
    $messages = mysqli_stmt_get_result($stmt);
} else {
    $messages = mysqli_query($conn, $query);
}

// Handle bulk actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['message_ids'])) {
    verify_csrf_token();
    
    $action = sanitize_input($_POST['action']);
    $message_ids = $_POST['message_ids'];
    
    // Validate message IDs
    $valid_ids = [];
    foreach ($message_ids as $id) {
        if (is_numeric($id)) {
            $valid_ids[] = (int)$id;
        }
    }
    
    if (!empty($valid_ids)) {
        $ids_str = implode(',', $valid_ids);
        
        if ($action === 'mark_handled') {
            $update_query = "UPDATE messages SET status = 'handled' WHERE id IN ($ids_str)";
            mysqli_query($conn, $update_query);
        } elseif ($action === 'mark_new') {
            $update_query = "UPDATE messages SET status = 'new' WHERE id IN ($ids_str)";
            mysqli_query($conn, $update_query);
        } elseif ($action === 'delete') {
            $delete_query = "DELETE FROM messages WHERE id IN ($ids_str)";
            mysqli_query($conn, $delete_query);
        }
        
        // Redirect to refresh the page
        header('Location: ' . $_SERVER['PHP_SELF'] . '?' . http_build_query($_GET));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages | Admin Dashboard</title>
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
                <h1 class="text-3xl font-bold">Messages</h1>
                
                <div class="flex space-x-2">
                    <a href="index.php" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-sm flex items-center transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>
            </div>
            
            <!-- Filters and Search -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
                <form action="" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-400 mb-1">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-500"></i>
                            </div>
                            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-700 bg-gray-700 rounded-md focus:outline-none focus:ring-primary focus:border-primary text-sm" 
                                   placeholder="Search name, email, subject...">
                        </div>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-400 mb-1">Status</label>
                        <select id="status" name="status" 
                                class="block w-full px-3 py-2 border border-gray-700 bg-gray-700 rounded-md focus:outline-none focus:ring-primary focus:border-primary text-sm">
                            <option value="">All Status</option>
                            <option value="new" <?php echo $status_filter === 'new' ? 'selected' : ''; ?>>New</option>
                            <option value="handled" <?php echo $status_filter === 'handled' ? 'selected' : ''; ?>>Handled</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-400 mb-1">Sort By</label>
                        <select id="sort" name="sort" 
                                class="block w-full px-3 py-2 border border-gray-700 bg-gray-700 rounded-md focus:outline-none focus:ring-primary focus:border-primary text-sm">
                            <option value="created_at" <?php echo $sort_by === 'created_at' ? 'selected' : ''; ?>>Date</option>
                            <option value="name" <?php echo $sort_by === 'name' ? 'selected' : ''; ?>>Name</option>
                            <option value="email" <?php echo $sort_by === 'email' ? 'selected' : ''; ?>>Email</option>
                            <option value="subject" <?php echo $sort_by === 'subject' ? 'selected' : ''; ?>>Subject</option>
                            <option value="status" <?php echo $sort_by === 'status' ? 'selected' : ''; ?>>Status</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-400 mb-1">Order</label>
                        <select id="order" name="order" 
                                class="block w-full px-3 py-2 border border-gray-700 bg-gray-700 rounded-md focus:outline-none focus:ring-primary focus:border-primary text-sm">
                            <option value="DESC" <?php echo $sort_order === 'DESC' ? 'selected' : ''; ?>>Descending</option>
                            <option value="ASC" <?php echo $sort_order === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-4 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary/90 rounded-lg text-sm flex items-center transition-colors">
                            <i class="fas fa-filter mr-2"></i> Apply Filters
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Messages Table -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
                <form action="" method="POST" id="messagesForm">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <?php if (mysqli_num_rows($messages) > 0): ?>
                        <div class="mb-4 flex flex-wrap gap-2">
                            <button type="button" id="selectAll" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-xs transition-colors">
                                Select All
                            </button>
                            <button type="button" id="deselectAll" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-xs transition-colors">
                                Deselect All
                            </button>
                            <button type="submit" name="action" value="mark_handled" class="px-3 py-1 bg-accent hover:bg-accent/90 rounded text-xs transition-colors">
                                Mark as Handled
                            </button>
                            <button type="submit" name="action" value="mark_new" class="px-3 py-1 bg-primary hover:bg-primary/90 rounded text-xs transition-colors">
                                Mark as New
                            </button>
                            <button type="submit" name="action" value="delete" class="px-3 py-1 bg-secondary hover:bg-secondary/90 rounded text-xs transition-colors" 
                                    onclick="return confirm('Are you sure you want to delete the selected messages?')">
                                Delete Selected
                            </button>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-gray-700 rounded-lg overflow-hidden">
                                <thead class="bg-gray-600">
                                    <tr>
                                        <th class="px-4 py-3 w-10">
                                            <div class="flex items-center">
                                                <input type="checkbox" id="selectAllCheckbox" class="rounded bg-gray-700 border-gray-600 text-primary focus:ring-primary">
                                            </div>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-600">
                                    <?php while ($message = mysqli_fetch_assoc($messages)): ?>
                                        <tr class="hover:bg-gray-600 transition-colors">
                                            <td class="px-4 py-4">
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="message_ids[]" value="<?php echo $message['id']; ?>" 
                                                           class="message-checkbox rounded bg-gray-700 border-gray-600 text-primary focus:ring-primary">
                                                </div>
                                            </td>
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
                                                <a href="process_message.php?action=toggle_status&id=<?php echo $message['id']; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" 
                                                   class="<?php echo $message['status'] === 'new' ? 'text-accent' : 'text-secondary'; ?> hover:text-opacity-80 mr-3">
                                                    <i class="fas <?php echo $message['status'] === 'new' ? 'fa-check' : 'fa-undo'; ?>"></i>
                                                </a>
                                                <a href="process_message.php?action=delete&id=<?php echo $message['id']; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" 
                                                   class="text-red-500 hover:text-red-400" 
                                                   onclick="return confirm('Are you sure you want to delete this message?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <div class="flex justify-between items-center mt-6">
                                <div class="text-sm text-gray-400">
                                    Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $per_page, $total_messages); ?> of <?php echo $total_messages; ?> messages
                                </div>
                                
                                <div class="flex space-x-1">
                                    <?php
                                    // Build pagination query string
                                    $query_params = $_GET;
                                    
                                    // Previous page link
                                    if ($page > 1) {
                                        $query_params['page'] = $page - 1;
                                        $prev_link = '?' . http_build_query($query_params);
                                        echo '<a href="' . $prev_link . '" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-sm transition-colors">';
                                        echo '<i class="fas fa-chevron-left"></i>';
                                        echo '</a>';
                                    }
                                    
                                    // Page number links
                                    $start_page = max(1, $page - 2);
                                    $end_page = min($total_pages, $page + 2);
                                    
                                    for ($i = $start_page; $i <= $end_page; $i++) {
                                        $query_params['page'] = $i;
                                        $page_link = '?' . http_build_query($query_params);
                                        
                                        $active_class = $i === $page ? 'bg-primary text-white' : 'bg-gray-700 hover:bg-gray-600';
                                        
                                        echo '<a href="' . $page_link . '" class="px-3 py-1 ' . $active_class . ' rounded text-sm transition-colors">';
                                        echo $i;
                                        echo '</a>';
                                    }
                                    
                                    // Next page link
                                    if ($page < $total_pages) {
                                        $query_params['page'] = $page + 1;
                                        $next_link = '?' . http_build_query($query_params);
                                        echo '<a href="' . $next_link . '" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-sm transition-colors">';
                                        echo '<i class="fas fa-chevron-right"></i>';
                                        echo '</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-12 text-gray-400">
                            <i class="fas fa-inbox text-5xl mb-4"></i>
                            <p class="text-xl">No messages found</p>
                            <?php if (!empty($search) || !empty($status_filter)): ?>
                                <p class="mt-2">Try adjusting your search or filter criteria</p>
                                <a href="messages.php" class="inline-block mt-4 px-4 py-2 bg-primary hover:bg-primary/90 rounded-lg text-sm transition-colors">
                                    Clear Filters
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </main>
    </div>
    
    <script>
        // Check for dark mode preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }
        
        // Select all functionality
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const messageCheckboxes = document.querySelectorAll('.message-checkbox');
        const selectAllBtn = document.getElementById('selectAll');
        const deselectAllBtn = document.getElementById('deselectAll');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                messageCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }
        
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function() {
                messageCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                if (selectAllCheckbox) selectAllCheckbox.checked = true;
            });
        }
        
        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', function() {
                messageCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                if (selectAllCheckbox) selectAllCheckbox.checked = false;
            });
        }
        
        // Update select all checkbox state when individual checkboxes change
        messageCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(messageCheckboxes).every(c => c.checked);
                const someChecked = Array.from(messageCheckboxes).some(c => c.checked);
                
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                }
            });
        });
    </script>
</body>
</html>