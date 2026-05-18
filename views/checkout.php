<!DOCTYPE html>
<html>
<head><title>Checkout - PC Shop</title></head>
<body>

<?php include 'views/navbar.php'; ?>

<div class="page-content">
    <h2>🛒 Checkout</h2>

    <!-- Show errors -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $e): ?>
                <p><?php echo $e; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-danger">Your cart is empty! <a href="index.php">Go shopping</a>.</div>
    <?php else: ?>

    <!-- Cart items table -->
    <div class="card">
        <h3>Your Cart Items</h3>
        <table>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($cart_items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>$<?php echo number_format($item['price'], 2); ?></td>
                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
            </tr>
        </table>
    </div>

    <!-- Payment form -->
    <div class="card">
        <h3>Select Payment Method</h3>
        <form method="POST" action="index.php?page=checkout" onsubmit="return validateCheckout()">

            <label>
                <input type="radio" name="payment_method" value="cash_on_delivery" style="width:auto;">
                🚚 Cash on Delivery
            </label><br><br>

            <label>
                <input type="radio" name="payment_method" value="online_wallet" style="width:auto;">
                💳 Online Wallet
            </label>

            <p id="pm_error" style="color:red; display:none;">Please select a payment method.</p>

            <br>
            <button type="submit" class="btn btn-success">Place Order - $<?php echo number_format($total, 2); ?></button>
        </form>
    </div>

    <?php endif; ?>
</div>

<script>
// JS Validation
function validateCheckout() {
    var selected = document.querySelector('input[name="payment_method"]:checked');
    if (!selected) {
        document.getElementById('pm_error').style.display = 'block';
        return false;
    }
    return true;
}
</script>

</body>
</html>
