<?php require APPROOT.'/views/inc/header.php';?>
    <!-- Top Navbar -->
     <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
    <h1>Posts</h1>
    <?php foreach($data['posts'] as $post): ?>
        <div class="post-index-container">
            <div class="post-user-name"><?php echo $post->user_name; ?></div>
            <div class="post-created-at"><?php echo $post->post_created_at; ?></div>
            <div class="post-body">
                <div class="post-title"><?php echo $post->title; ?></div>
                <div class="post-body"><?php echo $post->body; ?></div>
            </div>
        </div>

        <div class="post-footer">
            <div class="post-likes">likes 10</div>
            <div class="post-dislikes">Dislikes 0</div>
        </div>
    <?php endforeach; ?>


<?php require APPROOT.'/views/inc/footer.php';?>