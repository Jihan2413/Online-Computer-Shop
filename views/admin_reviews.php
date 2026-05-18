<!DOCTYPE html>
<html>
<head><title>Manage Reviews - PC Shop</title></head>
<body>

<?php include 'views/navbar.php'; ?>

<div class="page-content">
    <h2>💬 Manage Reviews</h2>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success">Review deleted successfully.</div>
    <?php endif; ?>

    <div class="card">
        <?php if (empty($reviews)): ?>
            <p>No reviews found.</p>
        <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Reviewer</th>
                <th>Comment</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php foreach ($reviews as $r): ?>
            <tr>
                <td><?php echo $r['id']; ?></td>
                <td><?php echo htmlspecialchars($r['product_name'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($r['reviewer_name']); ?></td>
                <td><?php echo htmlspecialchars(substr($r['comment'], 0, 80)); ?></td>
                <td><?php echo date('d M Y', strtotime($r['created_at'])); ?></td>
                <td>
                    <form method="POST" action="index.php?page=admin_reviews" onsubmit="return confirm('Delete this review?')">
                        <input type="hidden" name="delete_review_id" value="<?php echo $r['id']; ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
