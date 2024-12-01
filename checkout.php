<?php
session_start();
include 'config.php';

$productIds = [];
foreach (($_SESSION['cart'] ?? []) as $cartId => $cartValue) {
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
    <title>Checkout</title>
    <link rel="icon" href="icon/bag-check-fill.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <?php include 'include/menu.php' ?>
    <h3 class="text-center">Check out</h3>
    <form action="<?php echo $base_url; ?>/check-form.php" method="post">
        <div class="row g-5 ms-3">
            <div class="col-md-6 col-lg-7">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="fullname" class="form-label">Fullname</label>
                        <input type="text" name="fullname" class="form-control" required>
                    </div>
                    <div class="col-sm-6">
                        <label for="tel" class="form-label">Tel.</label>
                        <input type="text" name="tel" class="form-control" required>
                    </div>
                    <div class="col-sm-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>
                <hr class="my-4">
                <div class="text-end">
                    <a href="<?php echo $base_url; ?>/product_list.php" class="btn btn-secondary btn-lg" role="button">Back</a>
                    <button class="btn btn-primary btn-lg" type="submit">Continue</button>
                </div>
            </div>
            <div class="col-md-6 col-lg-5 order-md-last">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-primary">Your cart</span>
                </h4>
                <?php if ($rows > 0) : ?>
                    <ul class="list-group mb-3">
                        <?php $grand_total = 0; ?>
                        <?php while ($product = mysqli_fetch_assoc($query)) : ?>
                            <li class="list-group-item d-flex justify-content-between lh-sm">
                                <div>
                                    <h6 class="my-0"><?php echo $product['product_name']; ?>(<?php echo $_SESSION['cart'][$product['id']] ?>)</h6>
                                    <small class="text-body-secondary"><?php echo nl2br($product['detail']); ?></small>
                                    <input type="hidden" name="product[<?php echo $product['id'] ?>][price]" value="<?php echo $product['price']; ?>">
                                    <input type="hidden" name="product[<?php echo $product['id'] ?>][name]" value="<?php echo $product['product_name']; ?>">
                                    <input type="hidden" name="product[<?php echo $product['id'] ?>][qty]" value="<?php echo $_SESSION['cart'][$product['id']]; ?>">
                                </div>
                                <span class="text-body-secondary">฿ <?php echo number_format($_SESSION['cart'][$product['id']] * $product['price'], 2) ?></span>
                            </li>
                            <?php $grand_total += $_SESSION['cart'][$product['id']] * $product['price']; ?>
                        <?php endwhile; ?>
                        <li class="list-group-item d-flex justify-content-between bg-body-tertiary">
                            <div class="text-success">
                                <h6 class="my-0">Grand total</h6>
                                <small>amount</small>
                            </div>
                            <span class="text-success"><strong><?php echo '฿ ', number_format($grand_total, 2); ?></strong></span>
                        </li>
                    </ul>
                    <input type="hidden" name="grand_total" value="<?php echo $grand_total; ?>">
                <?php endif; ?>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+6d4o5LU1po/QdzUN1HT7u6VhPLVjLMfKsybm7Y" crossorigin="anonymous"></script>
</body>

</html>
