<?php
require_once 'config/database.php';


// Get all customers
function getAllCustomers()
{
    global $conn;

    $sql = "SELECT * FROM users WHERE role='customer' ORDER BY created_at DESC";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $customers = array();

    while($row = mysqli_fetch_assoc($result))
    {
        $customers[] = $row;
    }

    return $customers;
}


// Get user by id
function getUserById($id)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}


// Delete user cart
function deleteUserCart($user_id)
{
    global $conn;

    $sql = "DELETE FROM cart WHERE user_id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $user_id);

    return mysqli_stmt_execute($stmt);
}


// Delete reviews by user
function deleteReviewsByUser($user_id)
{
    global $conn;

    $sql = "DELETE FROM reviews WHERE user_id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $user_id);

    return mysqli_stmt_execute($stmt);
}


// Delete order items by user
function deleteOrderItemsByUser($user_id)
{
    global $conn;

    $sql = "DELETE FROM order_items 
            WHERE order_id IN (
                SELECT id FROM orders WHERE user_id=?
            )";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $user_id);

    return mysqli_stmt_execute($stmt);
}


// Delete orders by user
function deleteOrdersByUser($user_id)
{
    global $conn;

    $sql = "DELETE FROM orders WHERE user_id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $user_id);

    return mysqli_stmt_execute($stmt);
}


// Delete user
function deleteUser($id)
{
    global $conn;

    $sql = "DELETE FROM users WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    return mysqli_stmt_execute($stmt);
}

?>