<?php require APPROOT.'/views/blog/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/blog/blog.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>

    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
    <main class="blog-container">
        <!-- Featured Posts Section -->
        <section class="featured-section">
            <div class="featured-grid">
                <?php foreach($data['featured_posts'] as $index => $post): ?>
                    <article class="featured-card <?php echo $index === 0 ? 'featured-main' : 'featured-secondary'; ?>"
                            style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), 
                                    url('<?php echo URLROOT; ?>/public/uploads/blog/<?php echo $post->featured_image; ?>')">
                        <div class="featured-content">
                            <span class="category-tag"><?php echo $post->category_name; ?></span>
                            <h2><?php echo $post->title; ?></h2>
                            <p class="summary"><?php echo substr($post->summary, 0, 120) . '...'; ?></p>
                            <div class="meta">
                                <span class="date">
                                    <span class="material-icons-sharp">calendar_today</span>
                                    <?php echo date('M d, Y', strtotime($post->created_at)); ?>
                                </span>
                                <span class="views">
                                    <span class="material-icons-sharp">visibility</span>
                                    <?php echo $post->views; ?> views
                                </span>
                            </div>
                            <a href="<?php echo URLROOT; ?>/blog/showPost/<?php echo $post->slug; ?>" class="read-more">
                                Read More
                                <span class="material-icons-sharp">arrow_forward</span>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="categories-section">
            <div class="categories-container">
                <button class="nav-arrow prev" onclick="scrollCategories('left')" aria-label="Previous categories">
                    <span class="material-icons-sharp">chevron_left</span>
                </button>
                
                <div class="categories-wrapper">
                    <a href="<?php echo URLROOT; ?>/blog"
                    class="category-item <?php echo !$data['active_category'] ? 'active' : ''; ?>">
                        All Posts
                    </a>
                    <?php foreach($data['categories'] as $category): ?>
                        <a href="<?php echo URLROOT; ?>/blog?category=<?php echo $category->slug; ?>"
                        class="category-item <?php echo $data['active_category'] === $category->slug ? 'active' : ''; ?>">
                            <?php echo $category->name; ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <button class="nav-arrow next" onclick="scrollCategories('right')" aria-label="Next categories">
                    <span class="material-icons-sharp">chevron_right</span>
                </button>
            </div>
        </section>

        <!-- Posts Grid Section -->
        <section class="posts-section">
            <div class="posts-grid">
                <?php foreach($data['posts'] as $post): ?>
                    <article class="post-card">
                        <div class="post-image">
                            <img src="<?php echo URLROOT; ?>/public/uploads/blog/<?php echo $post->featured_image; ?>" 
                                alt="<?php echo $post->title; ?>">
                            <span class="category-badge"><?php echo $post->category_name; ?></span>
                        </div>
                        <div class="post-content">
                            <h3><?php echo $post->title; ?></h3>
                            <p><?php echo substr($post->summary, 0, 100) . '...'; ?></p>
                            <div class="post-meta">
                                <span class="date">
                                    <span class="material-icons-sharp">calendar_today</span>
                                    <?php echo date('M d, Y', strtotime($post->created_at)); ?>
                                </span>
                                <span class="views">
                                    <span class="material-icons-sharp">visibility</span>
                                    <?php echo $post->views; ?>
                                </span>
                            </div>
                            <a href="<?php echo URLROOT; ?>/blog/showPost/<?php echo $post->slug; ?>" class="read-more">
                                Read Article
                                <span class="material-icons-sharp">arrow_forward</span>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($data['pagination']['total_pages'] > 1): ?>
                <div class="pagination-wrapper">
                    <?php if ($data['pagination']['has_previous']): ?>
                        <a href="?page=<?php echo $data['pagination']['current_page'] - 1; ?><?php echo $data['active_category'] ? '&category=' . $data['active_category'] : ''; ?>" 
                        class="page-link">
                            <span class="material-icons-sharp">chevron_left</span>
                        </a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                        <a href="?page=<?php echo $i; ?><?php echo $data['active_category'] ? '&category=' . $data['active_category'] : ''; ?>" 
                        class="page-link <?php echo $i === $data['pagination']['current_page'] ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($data['pagination']['has_next']): ?>
                        <a href="?page=<?php echo $data['pagination']['current_page'] + 1; ?><?php echo $data['active_category'] ? '&category=' . $data['active_category'] : ''; ?>" 
                        class="page-link">
                            <span class="material-icons-sharp">chevron_right</span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php require APPROOT.'/views/inc/components/bottomfooter.php';?>
    <script src="<?php echo URLROOT; ?>/js/blog/home.js"></script>
<?php require APPROOT.'/views/blog/footer.php';?>