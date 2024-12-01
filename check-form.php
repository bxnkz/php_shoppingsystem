<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $tel = mysqli_real_escape_string($conn, $_POST['tel']);
    $grand_total = mysqli_real_escape_string($conn, $_POST['grand_total']);

    date_default_timezone_set('Asia/Bangkok');
    $now = date('Y-m-d H:i:s');

    // Insert order details into the orders table
    $query = "INSERT INTO orders (order_date, fullname, email, tel, grand_total) 
              VALUES ('$now', '$fullname', '$email', '$tel', '$grand_total')";

    if (mysqli_query($conn, $query)) {
        $order_id = mysqli_insert_id($conn); // Get the last inserted order ID

        // Insert each product in the order_products table
        foreach ($_POST['product'] as $productId => $productData) {
            $product_name = mysqli_real_escape_string($conn, $productData['name']);
            $price = mysqli_real_escape_string($conn, $productData['price']);
            $quantity = mysqli_real_escape_string($conn, $productData['qty']);
            $total = $price * $quantity;

            $query = "INSERT INTO order_details (order_id, product_id, product_name, price, quantity, total) 
                      VALUES ('$order_id', '$productId', '$product_name', '$price', '$quantity', '$total')";

            if (!mysqli_query($conn, $query)) {
                die('Product insertion failed: ' . mysqli_error($conn));
            }
        }

        // Clear the cart session after successful order placement
        unset($_SESSION['cart']);
        $_SESSION['message'] = 'Checkout success';
        header('Location: ' . $base_url . '/checkout-success.php');
        exit;
    } else {
        die('Order insertion failed: ' . mysqli_error($conn));
    }
} else {
    header('Location: ' . $base_url . '/checkout.php');
    exit;
}
?>
