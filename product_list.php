<?php
session_start();
include 'config.php';

$query = mysqli_query($conn, "SELECT * FROM products");
$rows = mysqli_num_rows($query);

if (!empty($_GET['id'])) {
    $query_product = mysqli_query($conn, "SELECT * FROM products WHERE id='{$_GET['id']}'");
    $row_product = mysqli_num_rows($query_product);

    if ($row_product == 0) {
        header('location' . $base_url . '/index.php');
    }
    $result = mysqli_fetch_assoc($query_product);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Product</title>
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

        <h4>Product List</h4>
        <br>
        <div class="row d-flex justify-content-center">
            <?php if ($rows > 0) : ?>
                <?php while ($product = mysqli_fetch_assoc($query)) : ?>
                    <div class="col-3 mb-3">
                        <div class="card" style="width: 18rem; height:100%">
                            <?php if (!empty($product['profile_image'])) : ?>
                                <img  src="<?php echo $base_url; ?>/upload_image/<?php echo $product['profile_image'] ?>" width="100" height="100%" alt="Image" class="card-img-top">
                            <?php else : ?>
                                <small><?php echo 'No Image'; ?></small>
                            <?php endif; ?>

                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                                <p class="card-text text-success fw-bold mb-0"><?php echo number_format($product['price'], 2); ?> Baht</p>
                                <p class="card-text text-muted"><?php echo nl2br($product['detail']); ?></p>
                                <a href="<?php echo $base_url; ?>/cart-add.php?id=<?php echo $product['id'];?>" class="btn btn-primary w-100">Add Cart</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <h4 class="text-danger">ไม่มีรายการสินค้า</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+6d4o5LU1po/QdzUN1HT7u6VhPLVjLMfKsybm7Y" crossorigin="anonymous"></script>
</body>

</html>