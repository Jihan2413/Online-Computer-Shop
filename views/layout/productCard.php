<?php

?>
<div class="product-card">
    <a href="<?php echo $rootPath; ?>product.php?id=<?php echo (int)$product['id']; ?>">
        <img
            src="<?php echo $rootPath; ?>public/uploads/<?php echo htmlspecialchars($product['image']); ?>"
            alt="<?php echo htmlspecialchars($product['name']); ?>"
            onerror="this.src='<?php echo $rootPath; ?>public/assets/css/no-image.png'"
        >
        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
    </a>
    <p class="review-snippet">
        <?php echo htmlspecialchars(mb_substr($product['manufacturer_review'], 0, 80)) . '…'; ?>
    </p>
    <p class="price">৳ <?php echo number_format($product['price'], 2); ?></p>
    <p class="stock <?php echo $product['stock'] > 0 ? 'in-stock' : 'out-stock'; ?>">
        <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
    </p>
    <a href="<?php echo $rootPath; ?>product.php?id=<?php echo (int)$product['id']; ?>" class="btn-secondary">
        View Details
    </a>
</div>
