<?php


    require_once('/../config/db.php');
    require_once('/productModel.php');

    
    function getCartByUser($userId) {
        $con  = getConnection();
        $sql  = "SELECT c.id, c.quantity,
                        p.id AS product_id, p.name, p.price, p.stock, p.image
                 FROM   cart c
                 JOIN   products p ON c.product_id = p.id
                 WHERE  c.user_id = ?
                 ORDER  BY c.id ASC";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }

        mysqli_close($con);
        return $items;
    }

    function getCartTotals($userId) {
        $items     = getCartByUser($userId);
        $total     = 0;
        $itemCount = 0;

        foreach ($items as $item) {
            $total     += $item['price'] * $item['quantity'];
            $itemCount += $item['quantity'];
        }

        return [
            'item_count' => $itemCount,
            'total'      => $total
        ];
    }

   
    function addToCart($userId, $productId, $quantity) {
        
        if (!is_numeric($quantity) || (int)$quantity < 1) {
            return ['success' => false, 'message' => 'Quantity must be a positive number.'];
        }
        $quantity = (int)$quantity;

        
        $stock = getProductStock($productId);
        if ($stock < 1) {
            return ['success' => false, 'message' => 'This product is out of stock.'];
        }

        $con = getConnection();

       
        $sqlCheck = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
        $stmtCheck = mysqli_prepare($con, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "ii", $userId, $productId);
        mysqli_stmt_execute($stmtCheck);
        $resultCheck = mysqli_stmt_get_result($stmtCheck);
        $existing    = mysqli_fetch_assoc($resultCheck);

        if ($existing) {
            $newQty = $existing['quantity'] + $quantity;

            if ($newQty > $stock) {
                mysqli_close($con);
                return ['success' => false, 'message' => "Only $stock units available in stock."];
            }

            $sqlUpdate = "UPDATE cart SET quantity = ? WHERE id = ?";
            $stmtUpdate = mysqli_prepare($con, $sqlUpdate);
            mysqli_stmt_bind_param($stmtUpdate, "ii", $newQty, $existing['id']);
            mysqli_stmt_execute($stmtUpdate);

        } else {
            if ($quantity > $stock) {
                mysqli_close($con);
                return ['success' => false, 'message' => "Only $stock units available in stock."];
            }

            $sqlInsert = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmtInsert = mysqli_prepare($con, $sqlInsert);
            mysqli_stmt_bind_param($stmtInsert, "iii", $userId, $productId, $quantity);
            mysqli_stmt_execute($stmtInsert);
        }

        mysqli_close($con);
        return ['success' => true, 'message' => 'Product added to cart.'];
    }

    
    function updateCartItem($cartId, $userId, $quantity) {
        if (!is_numeric($quantity) || (int)$quantity < 1) {
            return ['success' => false, 'message' => 'Quantity must be at least 1.'];
        }
        $quantity = (int)$quantity;

        $con = getConnection();

       
        $sqlGet = "SELECT c.product_id, p.stock
                   FROM cart c JOIN products p ON c.product_id = p.id
                   WHERE c.id = ? AND c.user_id = ?";
        $stmtGet = mysqli_prepare($con, $sqlGet);
        mysqli_stmt_bind_param($stmtGet, "ii", $cartId, $userId);
        mysqli_stmt_execute($stmtGet);
        $resultGet = mysqli_stmt_get_result($stmtGet);
        $row       = mysqli_fetch_assoc($resultGet);

        if (!$row) {
            mysqli_close($con);
            return ['success' => false, 'message' => 'Cart item not found.'];
        }

        if ($quantity > $row['stock']) {
            mysqli_close($con);
            return ['success' => false, 'message' => "Only {$row['stock']} units available."];
        }

        $sqlUpdate = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
        $stmtUpdate = mysqli_prepare($con, $sqlUpdate);
        mysqli_stmt_bind_param($stmtUpdate, "iii", $quantity, $cartId, $userId);
        mysqli_stmt_execute($stmtUpdate);

        mysqli_close($con);
        return ['success' => true, 'message' => 'Cart updated.'];
    }

   
    function removeCartItem($cartId, $userId) {
        $con  = getConnection();
        $sql  = "DELETE FROM cart WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $cartId, $userId);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_close($con);

        if ($affected > 0) {
            return ['success' => true,  'message' => 'Item removed from cart.'];
        } else {
            return ['success' => false, 'message' => 'Item not found.'];
        }
    }
?>
