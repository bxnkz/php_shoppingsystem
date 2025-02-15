<header class="d-flex justify-content-between py-3 sticky-top bg-light border-bottom shadow-sm">
    <ul class="nav nav-pills">
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li class="nav-item"><a href="<?php echo $base_url; ?>/index.php" class="nav-link">Manage Product</a></li>
        <?php endif; ?>
        <li class="nav-item"><a href="<?php echo $base_url; ?>/product_list.php" class="nav-link">Product List</a></li>
        <li class="nav-item"><a href="<?php echo $base_url; ?>/cart.php" class="nav-link">Cart(<?php echo count($_SESSION['cart'] ?? []) ?>)</a></li>
    </ul>

    <div class="d-flex align-items-center pe-3">
        <?php if (isset($_SESSION['username'])): ?>
            <span class="me-3">Welcome, <?php echo $_SESSION['username']; ?></span>
            <a href="#" class="btn btn-danger btn-sm" onclick="confirmLogout('<?php echo $base_url; ?>/logout.php')">Log Out</a>
        <?php endif; ?>
    </div>
</header>

<script>
    function confirmLogout(logoutUrl){
        if(confirm("Are you sure you want to log out ?")){
            window.location.href = logoutUrl;
        }
    }
</script>