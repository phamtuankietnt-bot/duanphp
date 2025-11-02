<?php include 'views/layout/header.php'; ?>

<main class="main">
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h2>Khám phá bộ sưu tập giày cao cấp</h2>
                <p>Thiết kế tinh tế, chất liệu cao cấp, trải nghiệm mua sắm sang trọng</p>
                <a href="index.php?action=products" class="btn btn-primary">Khám phá ngay</a>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured-products">
        <div class="container">
            <h2>Bộ sưu tập nổi bật</h2>
            <div class="products-grid">
                <?php foreach(array_slice($products, 0, 6) as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-price"><?php echo number_format($product['price']); ?> VNĐ</p>
                        <a href="index.php?action=productDetail&id=<?php echo $product['id']; ?>" class="btn btn-secondary">Xem chi tiết</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories">
        <div class="container">
            <h2>Danh mục cao cấp</h2>
            <div class="categories-grid">
                <div class="category-card">
                    <a href="index.php?action=products&category=Sneakers">
                        <i class="fas fa-running"></i>
                        <h3>Giày thể thao</h3>
                    </a>
                </div>
                <div class="category-card">
                    <a href="index.php?action=products&category=Boots">
                        <i class="fas fa-boot"></i>
                        <h3>Giày boot</h3>
                    </a>
                </div>
                <div class="category-card">
                    <a href="index.php?action=products&category=Sandals">
                        <i class="fas fa-shoe-prints"></i>
                        <h3>Dép sandal</h3>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'views/layout/footer.php'; ?>
