<!DOCTYPE html>
<html>
<head><title>Manage Customers - PC Shop</title></head>
<body>

<?php include 'views/navbar.php'; ?>

<div class="page-content">
    <h2>👥 Manage Customers</h2>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success">Customer deleted successfully.</div>
    <?php endif; ?>

    <div class="card">
        <?php if (empty($customers)): ?>
            <p>No customers found.</p>
        <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registered</th>
                <th>Action</th>
            </tr>
            <?php foreach ($customers as $c): ?>
            <tr>
                <td><?php echo $c['id']; ?></td>
                <td><?php echo htmlspecialchars($c['name']); ?></td>
                <td><?php echo htmlspecialchars($c['email']); ?></td>
                <td><?php echo date('d M Y', strtotime($c['created_at'])); ?></td>
                <td>
                    <form method="POST" action="index.php?page=admin_customers" onsubmit="return confirm('Delete this customer and all their data?')">
                        <input type="hidden" name="delete_user_id" value="<?php echo $c['id']; ?>">
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
