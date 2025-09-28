<?php
session_start();
require_once 'api/db_connection.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle search and filtering
$search = $_GET['search'] ?? '';
$role_filter = $_GET['role'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = 15;

// Handle user operations
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_user'])) {
        $user_id = intval($_POST['user_id']);
        $role = $_POST['role'];

        $stmt = $conn->prepare("UPDATE users SET role = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$role, $user_id]);
        $message = "User updated successfully!";
        $message_type = "success";
    }

    if (isset($_POST['delete_user'])) {
        $user_id = intval($_POST['user_id']);

        // Check if user has orders
        $stmt = $conn->prepare("SELECT COUNT(*) as order_count FROM orders WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $order_count = $stmt->fetch()['order_count'];

        if ($order_count > 0) {
            $message = "Cannot delete user with existing orders. Delete orders first.";
            $message_type = "error";
        } else {
            // Delete user addresses first
            $stmt = $conn->prepare("DELETE FROM addresses WHERE user_id = ?");
            $stmt->execute([$user_id]);

            // Delete user cart items
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->execute([$user_id]);

            // Delete user wishlist
            $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ?");
            $stmt->execute([$user_id]);

            // Delete remember tokens
            $stmt = $conn->prepare("DELETE FROM remember_tokens WHERE user_id = ?");
            $stmt->execute([$user_id]);

            // Finally delete user
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $message = "User deleted successfully!";
            $message_type = "success";
        }
    }

    if (isset($_POST['change_password'])) {
        $user_id = intval($_POST['user_id']);
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$new_password, $user_id]);
        $message = "Password changed successfully!";
        $message_type = "success";
    }
}

// Build query conditions
$conditions = [];
$params = [];

if (!empty($search)) {
    $conditions[] = "(name LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($role_filter)) {
    $conditions[] = "role = ?";
    $params[] = $role_filter;
}

$where_clause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM users $where_clause";
$stmt = $conn->prepare($count_query);
$stmt->execute($params);
$total_users = $stmt->fetch()['total'];
$total_pages = ceil($total_users / $per_page);

// Get users with pagination
$offset = ($page - 1) * $per_page;
$query = "SELECT u.*, COUNT(o.id) as order_count FROM users u LEFT JOIN orders o ON u.id = o.user_id $where_clause GROUP BY u.id ORDER BY u.created_at DESC LIMIT $per_page OFFSET $offset";
$stmt = $conn->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll();

// Get user for editing
$edit_user = null;
if (isset($_GET['edit'])) {
    $user_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $edit_user = $stmt->fetch();
}

// Get user statistics
$stmt = $conn->prepare("SELECT COUNT(*) as total_users FROM users");
$stmt->execute();
$stats['total_users'] = $stmt->fetch()['total_users'];

$stmt = $conn->prepare("SELECT COUNT(*) as admin_users FROM users WHERE role = 'admin'");
$stmt->execute();
$stats['admin_users'] = $stmt->fetch()['admin_users'];

$stmt = $conn->prepare("SELECT COUNT(*) as regular_users FROM users WHERE role = 'user'");
$stmt->execute();
$stats['regular_users'] = $stmt->fetch()['regular_users'];

$stmt = $conn->prepare("SELECT COUNT(*) as active_users FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$stmt->execute();
$stats['active_users'] = $stmt->fetch()['active_users'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - Admin Users</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container { display: flex; min-height: 100vh; }
        .admin-sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .admin-sidebar h2 { color: white; margin-bottom: 30px; text-align: center; }
        .admin-sidebar ul { list-style: none; padding: 0; }
        .admin-sidebar li { margin-bottom: 10px; }
        .admin-sidebar a { color: #ecf0f1; text-decoration: none; display: block; padding: 10px; border-radius: 4px; transition: background 0.3s; }
        .admin-sidebar a:hover, .admin-sidebar a.active { background: #34495e; }
        .admin-main {
            flex: 1;
            padding: 20px;
            background: #f5f5f5;
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }
        .hamburger-menu {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background: #2c3e50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
        }
        .hamburger-menu:hover { background: #34495e; }
        /* Tablet and Mobile Responsive Design */
        @media (max-width: 1200px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.show { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .hamburger-menu { display: block; }
            .admin-header h1 { margin-left: 50px; font-size: 1.5rem; }
            .admin-header { flex-direction: column; align-items: flex-start; gap: 15px; }
            .admin-actions { align-self: flex-end; }
            .filters-section { margin-bottom: 20px; }
            .filters-form { grid-template-columns: 1fr; gap: 12px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 15px; }
        }

        @media (max-width: 768px) {
            .admin-main { padding: 15px; }
            .admin-header h1 { font-size: 1.3rem; margin-left: 40px; }
            .admin-header { padding: 15px; margin-bottom: 20px; }
            .filters-section { padding: 15px; }
            .filters-form { gap: 10px; }
            .form-group input, .form-group select { padding: 10px; font-size: 16px; }
            .stats-grid { grid-template-columns: 1fr; gap: 15px; }
            .stat-card { padding: 15px; text-align: center; }
            .stat-number { font-size: 1.8rem; }
            .admin-table { font-size: 0.85rem; overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .admin-table th, .admin-table td { padding: 8px 6px; white-space: nowrap; }
            .admin-table th { font-size: 0.8rem; }
            .user-avatar { width: 35px; height: 35px; font-size: 0.8rem; }
            .role-badge { font-size: 0.7rem; padding: 3px 6px; }
            .btn { padding: 6px 12px; font-size: 0.85rem; min-width: 70px; }
            .hamburger-menu { top: 10px; left: 10px; padding: 8px; font-size: 16px; }
            .pagination { gap: 5px; }
            .pagination a, .pagination span { padding: 6px 10px; font-size: 0.85rem; }
            .modal-content { width: 95%; max-width: 500px; padding: 15px; }
        }

        @media (max-width: 480px) {
            .admin-main { padding: 10px; }
            .admin-header h1 { font-size: 1.2rem; margin-left: 35px; }
            .admin-header { padding: 10px; }
            .filters-section { padding: 12px; }
            .stats-grid { gap: 12px; }
            .stat-card { padding: 12px; }
            .stat-number { font-size: 1.5rem; }
            .admin-table { font-size: 0.8rem; }
            .admin-table th, .admin-table td { padding: 6px 4px; }
            .user-avatar { width: 30px; height: 30px; font-size: 0.7rem; }
            .btn { padding: 5px 10px; font-size: 0.8rem; }
            .role-badge { font-size: 0.65rem; padding: 2px 4px; }
            .pagination { flex-wrap: wrap; justify-content: center; }
            .pagination a, .pagination span { padding: 5px 8px; font-size: 0.8rem; }
            .modal-content { width: 98%; padding: 12px; }
            .form-group input, .form-group select { padding: 8px; }
            .admin-table th:nth-child(2), .admin-table td:nth-child(2) { max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            .admin-table th:nth-child(3), .admin-table td:nth-child(3) { max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .admin-table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .admin-table th, .admin-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        .admin-table th { background: #f8f9fa; font-weight: 600; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-success { color: #3c763d; background-color: #dff0d8; border-color: #d6e9c6; }
        .alert-error { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #d4a762; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: #000; }
        .btn:hover { opacity: 0.9; }
        .filters-section { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .filters-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: white; margin: 5% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close:hover { color: black; }
        .pagination { display: flex; justify-content: center; align-items: center; margin-top: 20px; gap: 10px; }
        .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333; }
        .pagination a:hover, .pagination .current { background: #d4a762; color: white; border-color: #d4a762; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
        .stat-number { font-size: 2rem; font-weight: bold; color: #d4a762; margin-bottom: 10px; }
        .role-badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 500; text-transform: uppercase; }
        .role-admin { background: #dc3545; color: white; }
        .role-user { background: #28a745; color: white; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: #d4a762; color: white; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; }
    </style>
</head>
<body>
    <button class="hamburger-menu" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="admin-container">
        <aside class="admin-sidebar" id="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="admin-orders.php"><i class="fas fa-shopping-bag"></i> Orders</a></li>
                <li><a href="admin-products.php"><i class="fas fa-tshirt"></i> Products</a></li>
                <li><a href="admin-users.php" class="active"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="index.php"><i class="fas fa-home"></i> Back to Site</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1>User Management</h1>
                <div class="admin-actions">
                    <button class="btn btn-secondary" id="refreshBtn"><i class="fas fa-sync"></i> Refresh</button>
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- User Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['total_users']); ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['admin_users']); ?></div>
                    <div class="stat-label">Admin Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['regular_users']); ?></div>
                    <div class="stat-label">Regular Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['active_users']); ?></div>
                    <div class="stat-label">Active (30 days)</div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <h3>Search & Filter Users</h3>
                <form method="GET" class="filters-form">
                    <div class="form-group">
                        <label for="search">Search</label>
                        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Name or email">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role">
                            <option value="">All Roles</option>
                            <option value="admin" <?php echo $role_filter == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="user" <?php echo $role_filter == 'user' ? 'selected' : ''; ?>>User</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="admin-users.php" class="btn btn-secondary" style="margin-left: 10px;">Clear</a>
                    </div>
                </form>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Orders</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="user-avatar">
                                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="role-badge role-<?php echo $user['role']; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td><?php echo $user['order_count']; ?></td>
                            <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <button class="btn btn-secondary" onclick='editUser(<?php echo json_encode($user); ?>)'>Edit</button>
                                
                                <form method="POST" style="display: inline-block; margin-left: 5px;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="delete_user" class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo urlencode($role_filter); ?>">&laquo; Previous</a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo urlencode($role_filter); ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo urlencode($role_filter); ?>">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div style="margin-top: 20px; color: #666;">
                Showing <?php echo ($offset + 1) . ' - ' . min($offset + $per_page, $total_users); ?> of <?php echo $total_users; ?> users
            </div>
        </main>
    </div>

    <!-- Edit User Modal -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('userModal')">&times;</span>
            <h2>Edit User</h2>
            <form method="POST">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="form-group">
                    <label for="edit_role">Role</label>
                    <select id="edit_role" name="role">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <button type="submit" name="update_user" class="btn btn-primary">Update User</button>
            </form>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('passwordModal')">&times;</span>
            <h2>Change Password</h2>
            <form method="POST">
                <input type="hidden" name="user_id" id="password_user_id">
                <div class="form-group">
                    <label for="new_password">New Password *</label>
                    <input type="password" id="new_password" name="new_password" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                </div>
                <button type="submit" name="change_password" class="btn btn-primary" onclick="return validatePassword()">Change Password</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('refreshBtn').addEventListener('click', function() {
            location.reload();
        });

        // Sidebar toggle functionality
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const hamburger = document.querySelector('.hamburger-menu');

            if (window.innerWidth <= 1200) {
                if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        function editUser(user) {
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_role').value = user.role;
            document.getElementById('userModal').style.display = 'block';
        }

        function changePassword(userId) {
            document.getElementById('password_user_id').value = userId;
            document.getElementById('passwordModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function validatePassword() {
            const password = document.getElementById('new_password').value;
            const confirm = document.getElementById('confirm_password').value;

            if (password !== confirm) {
                alert('Passwords do not match!');
                return false;
            }

            if (password.length < 6) {
                alert('Password must be at least 6 characters long!');
                return false;
            }

            return true;
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>
html>