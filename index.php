<?php
/**
 * Smart Serve Restaurant Ordering System
 * Admin Dashboard
 * 
 * DFD Level 0: Restaurant Admin - Sales Data & Analytics, Menu Updates & Reports
 */
require_once '../config/database.php';
require_once '../config/session.php';

requireAdminLogin();

$db = Database::getInstance();
$conn = $db->getConnection();

// Get dashboard statistics
$stats = [];

// Today's orders
$result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE DATE(order_time) = CURDATE()");
$stats['today_orders'] = $result->fetch_assoc()['count'];

// Today's revenue
$result = $conn->query("
    SELECT COALESCE(SUM(final_amount), 0) as total 
    FROM orders 
    WHERE DATE(order_time) = CURDATE() AND order_status = 'completed'
");
$stats['today_revenue'] = $result->fetch_assoc()['total'];

// Pending orders
$result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status IN ('pending', 'preparing')");
$stats['pending_orders'] = $result->fetch_assoc()['count'];

// Active tables
$result = $conn->query("SELECT COUNT(*) as count FROM tables WHERE status = 'occupied'");
$stats['active_tables'] = $result->fetch_assoc()['count'];

// Total tables
$result = $conn->query("SELECT COUNT(*) as count FROM tables");
$stats['total_tables'] = $result->fetch_assoc()['count'];

// Today's reservations
$result = $conn->query("SELECT COUNT(*) as count FROM reservations WHERE reservation_date = CURDATE()");
$stats['today_reservations'] = $result->fetch_assoc()['count'];

// Recent orders
$recentOrders = [];
$result = $conn->query("
    SELECT o.*, t.table_number 
    FROM orders o 
    JOIN tables t ON o.table_id = t.table_id 
    ORDER BY o.order_time DESC 
    LIMIT 10
");
while ($row = $result->fetch_assoc()) {
    $recentOrders[] = $row;
}

// Popular items today
$popularItems = [];
$result = $conn->query("
    SELECT m.item_name, SUM(oi.quantity) as total_qty
    FROM order_items oi
    JOIN menu_items m ON oi.item_id = m.item_id
    JOIN orders o ON oi.order_id = o.order_id
    WHERE DATE(o.order_time) = CURDATE()
    GROUP BY oi.item_id
    ORDER BY total_qty DESC
    LIMIT 5
");
while ($row = $result->fetch_assoc()) {
    $popularItems[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/admin-enhanced.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'includes/sidebar.php'; ?>

        <div class="admin-content">
            <div class="admin-header">
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
                <div class="admin-user">
                    <span>Welcome, <?php echo getAdminName(); ?></span>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm ms-2">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-icon bg-primary text-white">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-card-value"><?php echo $stats['today_orders']; ?></div>
                    <div class="stat-card-label">Today's Orders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon bg-success text-white">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="stat-card-value"><?php echo formatCurrency($stats['today_revenue']); ?></div>
                    <div class="stat-card-label">Today's Revenue</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon bg-warning text-white">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-card-value"><?php echo $stats['pending_orders']; ?></div>
                    <div class="stat-card-label">Pending Orders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon bg-info text-white">
                        <i class="fas fa-chair"></i>
                    </div>
                    <div class="stat-card-value"><?php echo $stats['active_tables']; ?>/<?php echo $stats['total_tables']; ?></div>
                    <div class="stat-card-label">Active Tables</div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Orders -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-list"></i> Recent Orders</span>
                            <a href="orders.php" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Table</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($recentOrders)): ?>
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">No orders yet</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($recentOrders as $order): ?>
                                                <tr>
                                                    <td><strong><?php echo $order['order_number']; ?></strong></td>
                                                    <td>Table <?php echo $order['table_number']; ?></td>
                                                    <td><?php echo formatCurrency($order['final_amount']); ?></td>
                                                    <td>
                                                        <span class="badge badge-<?php echo $order['order_status']; ?>">
                                                            <?php echo ucfirst($order['order_status']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo date('h:i A', strtotime($order['order_time'])); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popular Items & Quick Actions -->
                <div class="col-lg-4">
                    <!-- Popular Items -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-fire"></i> Popular Items Today
                        </div>
                        <div class="card-body">
                            <?php if (empty($popularItems)): ?>
                                <p class="text-muted text-center mb-0">No orders yet today</p>
                            <?php else: ?>
                                <?php foreach ($popularItems as $index => $item): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>
                                            <span class="badge bg-secondary me-2"><?php echo $index + 1; ?></span>
                                            <?php echo htmlspecialchars($item['item_name']); ?>
                                        </span>
                                        <span class="badge bg-primary"><?php echo $item['total_qty']; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    Quick Actions
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-bolt"></i> Quick Actions
                        </div>
                        <div class="card-body">
                            <a href="menu.php" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-utensils"></i> Manage Menu
                            </a>
                            <a href="tables.php" class="btn btn-outline-success w-100 mb-2">
                                <i class="fas fa-qrcode"></i> Generate QR Codes
                            </a>
                            <a href="../kitchen/" class="btn btn-outline-warning w-100 mb-2">
                                <i class="fas fa-fire"></i> Kitchen Dashboard
                            </a>
                            <a href="reports.php" class="btn btn-outline-info w-100">
                                <i class="fas fa-chart-bar"></i> View Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
