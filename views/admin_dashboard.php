<!DOCTYPE html>
<html>
<head><title>Admin Dashboard - PC Shop</title></head>
<body>

<?php include 'views/navbar.php'; ?>

<div class="page-content">
    <h2>📊 Admin Dashboard</h2>

    <!-- Quick links -->
    <div style="display:flex; gap:15px; margin-bottom:20px;">
        <a href="index.php?page=admin_customers" class="btn btn-primary">👥 Manage Customers</a>
        <a href="index.php?page=admin_reviews"   class="btn btn-primary">💬 Manage Reviews</a>
    </div>

    <!-- Recent Orders -->
    <div class="card">
        <h3>Recent Orders</h3>
        <?php if (empty($recent_orders)): ?>
            <p>No orders yet.</p>
        <?php else: ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
            <?php foreach ($recent_orders as $order): ?>
            <tr>
                <td>#<?php echo $order['id']; ?></td>
                <td><?php echo htmlspecialchars($order['customer_name'] ?? 'Deleted User'); ?></td>
                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                <td><?php echo $order['payment_method'] == 'cash_on_delivery' ? 'Cash on Delivery' : 'Online Wallet'; ?></td>
                <td><?php echo ucfirst($order['status']); ?></td>
                <td><?php echo date('d M Y', strtotime($order['order_date'])); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>

    <!-- Recent Reviews -->
    <div class="card">
        <h3>Recent Reviews</h3>
        <?php if (empty($recent_reviews)): ?>
            <p>No reviews yet.</p>
        <?php else: ?>
        <table>
            <tr>
                <th>Product</th>
                <th>Reviewer</th>
                <th>Comment</th>
                <th>Date</th>
            </tr>
            <?php foreach (array_slice($recent_reviews, 0, 10) as $review): ?>
            <tr>
                <td><?php echo htmlspecialchars($review['product_name'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($review['reviewer_name']); ?></td>
                <td><?php echo htmlspecialchars(substr($review['comment'], 0, 60)) . '...'; ?></td>
                <td><?php echo date('d M Y', strtotime($review['created_at'])); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
