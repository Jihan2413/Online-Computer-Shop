<?php

require_once('config/database.php');

function getCartItems($user_id){

    $con = getConnection();

    $sql = "SELECT cart.*,
                   products.name,
                   products.price,
                   products.stock,
                   products.image_path
            FROM cart
            JOIN products
            ON cart.product_id = products.id
            WHERE cart.user_id='{$user_id}'";

    $result = mysqli_query($con, $sql);

    $items = [];

    while($row = mysqli_fetch_assoc($result)){

        array_push($items, $row);

    }

    return $items;
}


function createOrder($user_id,
                     $total_amount,
                     $payment_method){

    $con = getConnection();

    $sql = "INSERT INTO orders
            (user_id,
             total_amount,
             payment_method,
             status,
             order_date)

            VALUES

            ('{$user_id}',
             '{$total_amount}',
             '{$payment_method}',
             'pending',
             NOW())";

    mysqli_query($con, $sql);

    return mysqli_insert_id($con);
}

function addOrderItem($order_id,
                      $product_id,
                      $quantity,
                      $unit_price){

    $con = getConnection();

    $sql = "INSERT INTO order_items
            (order_id,
             product_id,
             quantity,
             unit_price)

            VALUES

            ('{$order_id}',
             '{$product_id}',
             '{$quantity}',
             '{$unit_price}')";

    mysqli_query($con, $sql);
}


function clearCart($user_id){

    $con = getConnection();

    $sql = "DELETE FROM cart
            WHERE user_id='{$user_id}'";

    mysqli_query($con, $sql);
}


function getOrderById($order_id){

    $con = getConnection();

    $sql = "SELECT *
            FROM orders
            WHERE id='{$order_id}'";

    $result = mysqli_query($con, $sql);

    return mysqli_fetch_assoc($result);
}

function getOrderItems($order_id){

    $con = getConnection();

    $sql = "SELECT order_items.*,
                   products.name

            FROM order_items

            JOIN products
            ON order_items.product_id = products.id

            WHERE order_items.order_id='{$order_id}'";

    $result = mysqli_query($con, $sql);

    $items = [];

    while($row = mysqli_fetch_assoc($result)){

        array_push($items, $row);

    }

    return $items;
}


function getMyOrders($user_id){

    $con = getConnection();

    $sql = "SELECT *
            FROM orders
            WHERE user_id='{$user_id}'
            ORDER BY order_date DESC";

    $result = mysqli_query($con, $sql);

    $orders = [];

    while($row = mysqli_fetch_assoc($result)){

        array_push($orders, $row);

    }

    return $orders;
}


function getRecentOrders(){

    $con = getConnection();

    $sql = "SELECT orders.*,
                   users.name as customer_name

            FROM orders

            LEFT JOIN users
            ON orders.user_id = users.id

            ORDER BY order_date DESC
            LIMIT 10";

    $result = mysqli_query($con, $sql);

    $orders = [];

    while($row = mysqli_fetch_assoc($result)){

        array_push($orders, $row);

    }

    return $orders;
}

?>