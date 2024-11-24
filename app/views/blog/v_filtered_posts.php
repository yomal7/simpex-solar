<?php require APPROOT.'/views/blog/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/blog/blog.css">

<div class="container">
    <?php require APPROOT . '/views/blog/navbar.php'; ?>
    
    <div class="content">
        <?php if (empty($data['posts'])): ?>
            <div class="alert alert-info">
                No posts found for topic: <?php echo htmlspecialchars($data['topicName']); ?>
            </div>
        <?php else: ?>
            <h2 class="content-title">Posts in <?php echo htmlspecialchars($data['topicName']); ?></h2>
            <hr>
                <?php foreach ($data['posts'] as $post): ?>
                    <div class="post" style="margin-left: 0px;">
                        <?php if (!empty($post->image)): ?>
                            <img 
                                src="<?php echo URLROOT; ?>/public/assets/blog/<?php echo htmlspecialchars($post->image); ?>" 
                                class="post_image" 
                                alt="<?php echo htmlspecialchars($post->title); ?>"
                            >
                        <?php endif; ?>
                        
                        <a href="<?php echo URLROOT; ?>/blog/singlePost/<?php echo htmlspecialchars($post->slug); ?>">
                            <div class="post_info">
                                <h3><?php echo htmlspecialchars($post->title); ?></h3>
                                <div class="info">
                                    <span><?php echo date("F j, Y", strtotime($post->created_at)); ?></span>
                                    <span class="topic">
                                        <?php echo htmlspecialchars($post->topic->name); ?>
                                    </span>
                                    <span class="read_more">Read more...</span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT.'/views/blog/footer.php'; ?>
