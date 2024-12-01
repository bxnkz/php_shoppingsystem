<?php
session_start();
include 'config.php';

$query = mysqli_query($conn, "SELECT * FROM products");
$rows = mysqli_num_rows($query);

$result = [
    'id' => '',
    'product_name' => '',
    'price' => '',
    'detail' => '',
    'product_image' => '',
];

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

        <h4>Home-Manage Product</h4>
        <div class="row g-5">
            <div class="col-md-8 col-sm-12">
                <form action="<?php echo $base_url; ?>/product_form.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $result['id']; ?>">
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="" class="form-label">Product Name</label>
                            <input type="text" name="product_name" class="form-control" value="<?php echo $result['product_name']; ?>">
                        </div>
                        <div class="col-sm-6">
                            <label for="" class="form-label">Price</label>
                            <input type="text" name="price" class="form-control" value="<?php echo $result['price']; ?>">
                        </div>
                        <div class="col-sm-6">
                            <?php if (!empty($result['profile_image'])) : ?>
                                <div>
                                    <img style="width: 100px;" src="<?php echo $base_url; ?>/upload_image/<?php echo $result['profile_image'] ?>" alt="Image">
                                </div>

                            <?php endif; ?>
                            <label for="formFile" class="form-label">Image</label>
                            <input type="file" name="profile_image" class="form-control" accept="image/png,image/jpg,image/jpeg">
                        </div>
                        <div class="col-sm-12">
                            <label for="" class="form-label">Detail</label>
                            <textarea name="detail" rows="3" class="form-control" id=""><?php echo $result['detail']; ?></textarea>
                        </div>
                    </div>
                    <?php if (empty($result['id'])) : ?>
                        <button class="btn btn-primary" type="submit"><i class="bi bi-floppy-fill"></i> Create</button>
                    <?php else : ?>
                        <button class="btn btn-primary" type="submit"><i class="bi bi-floppy-fill"></i> Update</button>
                    <?php endif; ?>
                    <a role="button" class="btn btn-danger" type="submit" href="<?php echo $base_url ?>/index.php"><i class="bi bi-floppy-fill"></i> Cancel</a>
                </form>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered border-info">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Image</th>
                            <th>Product Name</th>
                            <th style="width: 200px">Price</th>
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
                                    <td>
                                        <a role="button" href="<?php echo $base_url ?>/index.php?id= <?php echo $product['id']; ?>" class="btn btn-outline-dark">Edit</a>
                                        <a onclick="return confirm('Are you sure delete');" role="button" href="<?php echo $base_url ?>/product-delete.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center text-danger">ไม่มีรายการสินค้า</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+6d4o5LU1po/QdzUN1HT7u6VhPLVjLMfKsybm7Y" crossorigin="anonymous"></script>
</body>

</html>