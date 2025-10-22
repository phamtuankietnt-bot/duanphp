<?php 
$page_title = 'Bài viết - ShoeStore';
include 'views/layout/header.php'; 
?>

<main class="main">
    <div class="container">
        <div class="page-header">
            <h1>Bài viết về giày</h1>
            <div class="breadcrumb">
                <a href="index.php">Trang chủ</a> > <span>Bài viết</span>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-buttons">
                <a href="index.php?action=articles" class="filter-btn <?php echo empty($_GET['category']) ? 'active' : ''; ?>">Tất cả</a>
                <a href="index.php?action=articles&category=Tin tức" class="filter-btn <?php echo ($_GET['category'] ?? '') == 'Tin tức' ? 'active' : ''; ?>">Tin tức</a>
                <a href="index.php?action=articles&category=Hướng dẫn" class="filter-btn <?php echo ($_GET['category'] ?? '') == 'Hướng dẫn' ? 'active' : ''; ?>">Hướng dẫn</a>
                <a href="index.php?action=articles&category=Xu hướng" class="filter-btn <?php echo ($_GET['category'] ?? '') == 'Xu hướng' ? 'active' : ''; ?>">Xu hướng</a>
                <a href="index.php?action=articles&category=Review" class="filter-btn <?php echo ($_GET['category'] ?? '') == 'Review' ? 'active' : ''; ?>">Review</a>
            </div>
        </div>

        <!-- Articles Grid -->
        <div class="articles-section">
            <?php if (!empty($articles)): ?>
                <div class="articles-grid">
                    <?php foreach($articles as $article): ?>
                    <article class="article-card">
                        <div class="article-image">
                            <img src="<?php echo $article['image']; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                            <div class="article-category"><?php echo htmlspecialchars($article['category']); ?></div>
                        </div>
                        <div class="article-content">
                            <h2 class="article-title">
                                <a href="index.php?action=articleDetail&id=<?php echo $article['id']; ?>">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </a>
                            </h2>
                            <p class="article-excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                            <div class="article-meta">
                                <span class="article-author">
                                    <i class="fas fa-user"></i>
                                    <?php echo htmlspecialchars($article['author']); ?>
                                </span>
                                <span class="article-date">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo date('d/m/Y', strtotime($article['created_at'])); ?>
                                </span>
                            </div>
                            <a href="index.php?action=articleDetail&id=<?php echo $article['id']; ?>" class="btn btn-primary btn-sm">Đọc thêm</a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-articles">
                    <div class="no-articles-content">
                        <i class="fas fa-newspaper"></i>
                        <h2>Chưa có bài viết nào</h2>
                        <p>Chúng tôi đang cập nhật nội dung mới. Vui lòng quay lại sau!</p>
                        <a href="index.php" class="btn btn-primary">Về trang chủ</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'views/layout/footer.php'; ?>
