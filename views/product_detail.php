<?php 
$page_title = $product->name . ' - ShoeStore';
include 'views/layout/header.php'; 
?>

<main class="main">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Trang chủ</a> > 
            <a href="index.php?action=products">Sản phẩm</a> > 
            <span><?php echo htmlspecialchars($product->name); ?></span>
        </div>

        <div class="product-detail">
            <div class="product-images">
                <img src="<?php echo $product->image; ?>" alt="<?php echo htmlspecialchars($product->name); ?>" class="main-image">
            </div>
            
            <div class="product-info">
                <h1><?php echo htmlspecialchars($product->name); ?></h1>
                <p class="product-category"><?php echo htmlspecialchars($product->category); ?></p>
                <p class="product-price"><?php echo number_format($product->price); ?> VNĐ</p>
                
                <div class="product-description">
                    <h3>Mô tả sản phẩm</h3>
                    <p><?php echo nl2br(htmlspecialchars($product->description)); ?></p>
                </div>

                <div class="product-actions">
                    <div class="add-to-cart-form">
                        <div class="quantity-selector">
                            <label>Số lượng:</label>
                            <input type="number" id="quantity" value="1" min="1" max="10">
                        </div>
                        <button type="button" onclick="addToCart(<?php echo $product->id; ?>, parseInt(document.getElementById('quantity').value))" class="btn btn-primary btn-large">Thêm vào giỏ hàng</button>
                    </div>
                </div>

                <div class="product-features">
                    <h3>Tính năng nổi bật</h3>
                    <ul>
                        <li>Chất liệu cao cấp</li>
                        <li>Thiết kế thời trang</li>
                        <li>Thoải mái khi sử dụng</li>
                        <li>Bảo hành 12 tháng</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>


<?php include 'views/layout/footer.php'; ?>
