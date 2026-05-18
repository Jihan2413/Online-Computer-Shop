<!DOCTYPE html>
<html>
<head><title>My Orders - PC Shop</title></head>
<body>

<?php include 'views/navbar.php'; ?>

<div class="page-content">
    <h2>📦 My Orders</h2>

    <?php if (empty($orders)): ?>
        <div class="alert alert-danger">You have no orders yet. <a href="index.php">Start shopping!</a></div>
    <?php else: ?>
    <div class="card">
        <table>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
            </tr>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?php echo $order['id']; ?></td>
                <td><?php echo date('d M Y', strtotime($order['order_date'])); ?></td>
                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                <td><?php echo $order['payment_method'] == 'cash_on_delivery' ? '🚚 Cash on Delivery' : '💳 Online Wallet'; ?></td>
                <td><?php echo ucfirst($order['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
</div>

</body>
</html>
