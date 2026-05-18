<?php

$order = [
    'id' => 1,
    'payment_method' => 'cash_on_delivery',
    'status' => 'pending',
    'total_amount' => 500
];

$order_items = [];

?>
<!DOCTYPE html>
<html>
<head><title>Order Confirmed - PC Shop</title></head>
<body>

<?php include 'views/navbar.php'; ?>

<div class="page-content">
    <div class="card" style="text-align:center; padding: 40px;">
        <h1>✅ Order Placed Successfully!</h1>
        <p>Order ID: <strong>#<?php echo $order['id']; ?></strong></p>
        <p>Payment: <strong><?php echo $order['payment_method'] == 'cash_on_delivery' ? '🚚 Cash on Delivery' : '💳 Online Wallet'; ?></strong></p>
        <p>Status: <strong><?php echo ucfirst($order['status']); ?></strong></p>
    </div>

    <div class="card">
        <h3>Order Items</h3>
        <table>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($order_items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                <td>$<?php echo number_format($item['unit_price'] * $item['quantity'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
            </tr>
        </table>
    </div>

    <a href="index.php?page=my_orders" class="btn btn-primary">View My Orders</a>
    <a href="index.php" class="btn btn-success" style="margin-left:10px;">Continue Shopping</a>
</div>

</body>
</html>
