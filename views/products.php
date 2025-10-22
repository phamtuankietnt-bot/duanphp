<?php 
$page_title = 'Sản phẩm - ShoeStore';
include 'views/layout/header.php'; 
?>

<main class="main">
    <div class="container">
        <div class="page-header">
            <h1>Sản phẩm</h1>
            <div class="breadcrumb">
                <a href="index.php">Trang chủ</a> > <span>Sản phẩm</span>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-buttons">
                <a href="index.php?action=products" class="filter-btn <?php echo empty($_GET['category']) ? 'active' : ''; ?>">Tất cả</a>
                <a href="index.php?action=products&category=Sneakers" class="filter-btn <?php echo ($_GET['category'] ?? '') == 'Sneakers' ? 'active' : ''; ?>">Giày thể thao</a>
                <a href="index.php?action=products&category=Boots" class="filter-btn <?php echo ($_GET['category'] ?? '') == 'Boots' ? 'active' : ''; ?>">Giày boot</a>
                <a href="index.php?action=products&category=Sandals" class="filter-btn <?php echo ($_GET['category'] ?? '') == 'Sandals' ? 'active' : ''; ?>">Dép sandal</a>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-section">
            <?php if (!empty($products)): ?>
                <div class="products-grid">
                    <?php foreach($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-category"><?php echo htmlspecialchars($product['category']); ?></p>
                            <p class="product-price"><?php echo number_format($product['price']); ?> VNĐ</p>
                            <a href="index.php?action=productDetail&id=<?php echo $product['id']; ?>" class="btn btn-primary">Xem chi tiết</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-products">
                    <p>Không tìm thấy sản phẩm nào.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'views/layout/footer.php'; ?>
