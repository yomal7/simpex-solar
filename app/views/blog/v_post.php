<?php require APPROOT.'/views/blog/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/blog/blog-post.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>

    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
    <main class="single-post">
        <!-- Back Navigation -->
        <div class="post-navigation">
            <a href="<?php echo URLROOT; ?>/blog" class="back-link">
                <span class="material-icons-sharp">arrow_back</span>
                Back to Blog
            </a>
            <div class="post-category">
                <a href="<?php echo URLROOT; ?>/blog?category=<?php echo $data['post']->category_slug; ?>">
                    <?php echo $data['post']->category_name; ?>
                </a>
            </div>
        </div>

        <article class="post-content">
            <!-- Post Header -->
            <header class="post-header">
                <h1><?php echo $data['post']->title; ?></h1>
                <div class="post-meta">
                    <div class="meta-item">
                        <span class="material-icons-sharp">calendar_today</span>
                        <?php echo date('F j, Y', strtotime($data['post']->created_at)); ?>
                    </div>
                    <div class="meta-item">
                        <span class="material-icons-sharp">visibility</span>
                        <?php echo $data['post']->views; ?> views
                    </div>
                    <div class="meta-item">
                        <span class="material-icons-sharp">schedule</span>
                        <?php echo $data['post']->read_time; ?> read
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            <?php if($data['post']->featured_image): ?>
                <div class="featured-image">
                    <img src="<?php echo URLROOT; ?>/public/uploads/blog/<?php echo $data['post']->featured_image; ?>" 
                        alt="<?php echo $data['post']->title; ?>">
                </div>
            <?php endif; ?>

            <!-- Post Body -->
            <div class="post-body">
                <?php echo $data['post']->body; ?>
            </div>

            <!-- Share Section -->
            <div class="share-section">
                
                <div class="share-buttons">
                    <h3>Share this post</h3>
                    <button onclick="sharePost('facebook')" class="share-btn facebook">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                    <button onclick="sharePost('twitter')" class="share-btn twitter">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button onclick="sharePost('linkedin')" class="share-btn linkedin">
                        <i class="fab fa-linkedin-in"></i>
                    </button>
                    <button onclick="copyLink()" class="share-btn copy-link">
                        <span class="material-icons-sharp">content_copy</span>
                    </button>
                </div>
            </div>
        </article>

        <!-- Related Posts -->
        <?php if(!empty($data['related_posts'])): ?>
            <section class="related-posts">
                <h2>Related Posts</h2>
                <div class="related-grid">
                    <?php foreach($data['related_posts'] as $post): ?>
                        <article class="related-card">
                            <?php if($post->featured_image): ?>
                                <div class="card-image">
                                    <img src="<?php echo URLROOT; ?>/public/uploads/blog/<?php echo $post->featured_image; ?>" 
                                        alt="<?php echo $post->title; ?>">
                                </div>
                            <?php endif; ?>
                            <div class="card-content">
                                <h3><?php echo $post->title; ?></h3>
                                <p><?php echo substr($post->summary, 0, 100) . '...'; ?></p>
                                <a href="<?php echo URLROOT; ?>/blog/showPost/<?php echo $post->slug; ?>" class="read-more">
                                    Read More
                                    <span class="material-icons-sharp">arrow_forward</span>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>
    <?php require APPROOT.'/views/inc/components/bottomfooter.php';?>                       
    <script src="<?php echo URLROOT; ?>/js/blog/post.js"></script>
<?php require APPROOT . '/views/blog/footer.php'; ?>