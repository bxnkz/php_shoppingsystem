<?php
session_start();
include 'config.php';

$productIds = [];
foreach (($_SESSION['cart'] ?? [])  as $cartId => $cartQty) {
    $productIds[] = $cartId;
}

$ids = 0;
if (count($productIds) > 0) {
    $ids = implode(',', $productIds);
}



$query = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids)");
$rows = mysqli_num_rows($query);



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="icon" href="icon/bag-check-fill.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-body-tertiary">
    <?php include 'include/menu.php'; ?>
    <div class="container" style="margin-top: 30px;">
        <?php if (!empty($_SESSION['message'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <h4>Cart</h4>
        <br>
        <div class="row">
            <div class="col-12">
                <form action="<?php echo $base_url;?>/cart-update.php" method="post">
                    <table class="table table-bordered border-info">
                        <thead>
                            <tr>
                                <th style="width: 100px;">Image</th>
                                <th>Product Name</th>
                                <th style="width: 200px">Price</th>
                                <th style="width: 100px">Quantity</th>
                                <th style="width: 120px">Total</th>
                                <th style="width: 200px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($rows > 0) : ?>
                                <?php while ($product = mysqli_fetch_assoc($query)) : ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($product['profile_image'])) : ?>
                                                <img width="100" src="<?php echo $base_url; ?>/upload_image/<?php echo $product['profile_image'] ?>" alt="Image">
                                            <?php else : ?>
                                                <small><?php echo 'No Image'; ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo $product['product_name']; ?>
                                            <div>
                                                <small class="text-muted"><?php echo nl2br($product['detail']); ?></small>
                                            </div>
                                        </td>
                                        <td><?php echo number_format($product['price'], 2); ?></td>
                                        <td><input type="number" name="product[<?php echo $product['id']; ?>][quantity]" value="<?php echo $_SESSION['cart'][$product['id']]; ?>" class="form-control"></td>

                                        <td>
                                            <?php echo number_format($product['price'] * $_SESSION['cart'][$product['id']], 2); ?>
                                        </td>
                                        <td>
                                            <a onclick="return confirm('Are you sure delete');" role="button" href="<?php echo $base_url ?>/cart-delete.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                <tr>
                                    <td colspan="6" class="text-end">
                                        <button type="submit" class="btn btn-lg btn-success">Update Cart</button>
                                        <a href="<?php echo $base_url; ?>/checkout.php" class="btn btn-lg btn-primary">Checkout order</a>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center text-danger">ไม่มีรายการสินค้า</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+6d4o5LU1po/QdzUN1HT7u6VhPLVjLMfKsybm7Y" crossorigin="anonymous"></script>
</body>

</html>