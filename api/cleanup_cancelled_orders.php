<?php
// cleanup_cancelled_orders.php
// This script should be run periodically (e.g., via cron job) to remove cancelled orders after 24 hours

require_once 'config.php';

try {
    // Calculate the cutoff time (24 hours ago)
    $cutoffTime = date('Y-m-d H:i:s', strtotime('-24 hours'));

    // First, get all cancelled orders older than 24 hours
    $stmt = $conn->prepare("
        SELECT id, order_number, user_id
        FROM orders
        WHERE status = 'cancelled'
        AND updated_at < ?
    ");
    $stmt->execute([$cutoffTime]);
    $cancelledOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cancelledOrders)) {
        echo "No cancelled orders to clean up.\n";
        exit(0);
    }

    $deletedCount = 0;
    $orderIds = [];

    // Delete order items first, then orders
    foreach ($cancelledOrders as $order) {
        // Delete order items
        $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt->execute([$order['id']]);

        // Delete the order
        $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$order['id']]);

        $orderIds[] = $order['order_number'];
        $deletedCount++;
    }

    // Log the cleanup
    $logMessage = sprintf(
        "[%s] Cleaned up %d cancelled orders: %s\n",
        date('Y-m-d H:i:s'),
        $deletedCount,
        implode(', ', $orderIds)
    );

    // You can log this to a file or database
    file_put_contents('logs/order_cleanup.log', $logMessage, FILE_APPEND);

    echo "Successfully cleaned up $deletedCount cancelled orders.\n";

} catch (PDOException $e) {
    error_log("Order cleanup error: " . $e->getMessage());

    // Log the error
    $errorMessage = sprintf(
        "[%s] Order cleanup error: %s\n",
        date('Y-m-d H:i:s'),
        $e->getMessage()
    );
    file_put_contents('logs/order_cleanup_error.log', $errorMessage, FILE_APPEND);

    echo "Error during order cleanup: " . $e->getMessage() . "\n";
    exit(1);
}
?>