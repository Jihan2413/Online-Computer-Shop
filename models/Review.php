<?php
require_once 'config/database.php';

// Get all reviews for a product
function getReviewsByProduct($product_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC");
    $stmt->execute([$product_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Add a new review
function addReview($product_id, $user_id, $reviewer_name, $comment) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, reviewer_name, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$product_id, $user_id, $reviewer_name, $comment]);
    return $conn->lastInsertId();
}

// Get a single review by id
function getReviewById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Delete a review
function deleteReview($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->execute([$id]);
}

// Get all reviews (for admin)
function getAllReviews($conn)
{
    $sql = "SELECT * FROM reviews ORDER BY created_at DESC";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $reviews = [];

    while($row = mysqli_fetch_assoc($result))
    {
        $reviews[] = $row;
    }

    return $reviews;
}

// Delete all reviews by a user

?>
