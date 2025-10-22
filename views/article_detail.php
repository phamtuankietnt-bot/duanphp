<?php 
$page_title = $article->title . ' - ShoeStore';
include 'views/layout/header.php'; 
?>

<main class="main">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Trang chủ</a> > 
            <a href="index.php?action=articles">Bài viết</a> > 
            <span><?php echo htmlspecialchars($article->title); ?></span>
        </div>

        <div class="article-detail">
            <article class="article-content">
                <header class="article-header">
                    <div class="article-category"><?php echo htmlspecialchars($article->category); ?></div>
                    <h1 class="article-title"><?php echo htmlspecialchars($article->title); ?></h1>
                    <div class="article-meta">
                        <span class="article-author">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($article->author); ?>
                        </span>
                        <span class="article-date">
                            <i class="fas fa-calendar"></i>
                            <?php echo date('d/m/Y H:i', strtotime($article->created_at)); ?>
                        </span>
                    </div>
                </header>

                <div class="article-image">
                    <img src="<?php echo $article->image; ?>" alt="<?php echo htmlspecialchars($article->title); ?>">
                </div>

                <div class="article-body">
                    <?php echo nl2br(htmlspecialchars($article->content)); ?>
                </div>

                <footer class="article-footer">
                    <div class="article-tags">
                        <span class="tag"><?php echo htmlspecialchars($article->category); ?></span>
                    </div>
                    <div class="article-share">
                        <span>Chia sẻ:</span>
                        <a href="#" class="share-btn facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="share-btn twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="share-btn linkedin"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </footer>
            </article>

            <?php if (!empty($related_articles)): ?>
            <aside class="related-articles">
                <h3>Bài viết liên quan</h3>
                <div class="related-grid">
                    <?php foreach($related_articles as $related): ?>
                    <div class="related-article">
                        <div class="related-image">
                            <img src="<?php echo $related['image']; ?>" alt="<?php echo htmlspecialchars($related['title']); ?>">
                        </div>
                        <div class="related-content">
                            <h4>
                                <a href="index.php?action=articleDetail&id=<?php echo $related['id']; ?>">
                                    <?php echo htmlspecialchars($related['title']); ?>
                                </a>
                            </h4>
                            <p><?php echo htmlspecialchars($related['excerpt']); ?></p>
                            <span class="related-date"><?php echo date('d/m/Y', strtotime($related['created_at'])); ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </aside>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'views/layout/footer.php'; ?>
