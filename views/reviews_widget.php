<?php
session_start();

require_once 'config/database.php';
require_once 'models/Review.php';

$conn = getConnection();

$product_id = 1;

// Get reviews (MYSQLI style)
$reviews = getReviewsByProduct($conn, $product_id);
?>

<div style="margin-top: 30px;">
    <h3>💬 Customer Reviews</h3>
    <hr>

    <div id="review-list">

        <?php if (empty($reviews)): ?>
            <p id="no-reviews-msg">No reviews yet. Be the first!</p>
        <?php else: ?>

            <?php foreach ($reviews as $r): ?>
                <div id="review-<?php echo $r['id']; ?>"
                     style="border:1px solid #ddd; padding:12px; margin-bottom:10px; border-radius:5px; background:white;">

                    <strong><?php echo htmlspecialchars($r['reviewer_name']); ?></strong>

                    <span style="color:#999; font-size:13px; margin-left:10px;">
                        <?php echo date('d M Y', strtotime($r['created_at'])); ?>
                    </span>

                    <?php if (isset($_SESSION['user_id']) &&
                        ($_SESSION['role'] == 'admin' || $r['user_id'] == $_SESSION['user_id'])): ?>

                        <button onclick="deleteReview(<?php echo $r['id']; ?>)"
                                style="float:right; background:#dc3545; color:white; border:none; padding:4px 10px; border-radius:4px; cursor:pointer;">
                            Delete
                        </button>

                    <?php endif; ?>

                    <p style="margin-top:8px;">
                        <?php echo htmlspecialchars($r['comment']); ?>
                    </p>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <hr>

    <!-- REVIEW FORM -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'customer'): ?>

        <h4>Write a Review</h4>

        <div id="review-msg" style="margin-bottom:10px;"></div>

        <form id="review-form">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

            <textarea name="comment" id="review-comment" rows="4"
                      placeholder="Write your review here..."
                      style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;"></textarea>

            <p id="comment-error" style="color:red; display:none;">Comment cannot be empty.</p>
            <p id="comment-length-error" style="color:red; display:none;">Max 1000 characters allowed.</p>

            <p style="color:#999; font-size:13px;">
                <span id="char-count">0</span>/1000 characters
            </p>

            <button type="button" onclick="submitReview()"
                    style="margin-top:5px; padding:8px 12px;">
                Submit Review
            </button>
        </form>

    <?php else: ?>
        <p><a href="index.php?page=demo_login">Login</a> to write a review.</p>
    <?php endif; ?>
</div>

<script>

// character counter
document.getElementById('review-comment').addEventListener('input', function () {
    document.getElementById('char-count').textContent = this.value.length;
});


// ---------------- SUBMIT REVIEW ----------------
function submitReview() {

    var comment = document.getElementById('review-comment').value.trim();
    var productId = document.querySelector('input[name="product_id"]').value;

    document.getElementById('comment-error').style.display = 'none';
    document.getElementById('comment-length-error').style.display = 'none';

    if (comment === '') {
        document.getElementById('comment-error').style.display = 'block';
        return;
    }

    if (comment.length > 1000) {
        document.getElementById('comment-length-error').style.display = 'block';
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'index.php?page=add_review', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {

        var data = JSON.parse(xhr.responseText);

        if (data.success) {

            var list = document.getElementById('review-list');
            var noMsg = document.getElementById('no-reviews-msg');
            if (noMsg) noMsg.remove();

            var div = document.createElement('div');
            div.id = 'review-' + data.id;
            div.style = 'border:1px solid #ddd; padding:12px; margin-bottom:10px; border-radius:5px; background:white;';

            div.innerHTML =
                '<strong>' + data.reviewer_name + '</strong>' +
                '<span style="color:#999;font-size:13px;margin-left:10px;">Just now</span>' +
                '<button onclick="deleteReview(' + data.id + ')" ' +
                'style="float:right;background:#dc3545;color:white;border:none;padding:4px 10px;border-radius:4px;cursor:pointer;">Delete</button>' +
                '<p style="margin-top:8px;">' + data.comment + '</p>';

            list.prepend(div);

            document.getElementById('review-comment').value = '';
            document.getElementById('char-count').textContent = '0';

            document.getElementById('review-msg').innerHTML =
                '<p style="color:green;">Review posted!</p>';

        } else {
            document.getElementById('review-msg').innerHTML =
                '<p style="color:red;">' + data.error + '</p>';
        }
    };

    xhr.send('product_id=' + productId + '&comment=' + encodeURIComponent(comment));
}


// ---------------- DELETE REVIEW ----------------
function deleteReview(id) {

    if (!confirm('Delete this review?')) return;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'index.php?page=delete_review', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {

        var data = JSON.parse(xhr.responseText);

        if (data.success) {
            var el = document.getElementById('review-' + id);
            if (el) el.remove();
        } else {
            alert(data.error);
        }
    };

    xhr.send('review_id=' + id);
}

</script>